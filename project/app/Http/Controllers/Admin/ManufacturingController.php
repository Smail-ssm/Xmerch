<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class ManufacturingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Manufacturing Dashboard - Production overview with KPIs
     */
    public function dashboard()
    {
        // Production metrics
        $today = today();
        
        // Get all POD products with capacity
        $podProducts = Product::pod()
            ->withPositiveProductionCap()
            ->get();
        
        // Calculate daily capacity utilization
        $totalCapacity = $podProducts->sum('production_cap');
        $capacityUsed = 0;
        $productsAtCapacity = 0;
        $dailyQuantities = $this->getTodayPodQuantities($today);
        
        foreach ($podProducts as $product) {
            $dailyOrders = (int) ($dailyQuantities[$product->id] ?? 0);
            
            $capacityUsed += $dailyOrders;
            
            if ($product->production_cap > 0 && $dailyOrders >= $product->production_cap) {
                $productsAtCapacity++;
            }
        }
        
        $stats = [
            'total_capacity' => $totalCapacity,
            'capacity_used' => $capacityUsed,
            'capacity_available' => max(0, $totalCapacity - $capacityUsed),
            'capacity_percentage' => $totalCapacity > 0 ? round(($capacityUsed / $totalCapacity) * 100) : 0,
            'products_at_capacity' => $productsAtCapacity,
            'total_pod_products' => $podProducts->count(),
            'pending_orders' => Order::pendingPrint()->count(),
            'printing_orders' => Order::printing()->count(),
            'printed_today' => Order::printed()->whereDate('printed_at', $today)->count(),
            'shipped_today' => Order::shippedPrint()->whereDate('shipped_at', $today)->count(),
        ];
        
        // Production rate (orders per hour - last 24 hours)
        $ordersLast24h = Order::where('print_status', 'printed')
            ->where('printed_at', '>=', now()->subHours(24))
            ->count();
        $stats['production_rate'] = round($ordersLast24h / 24, 1);
        
        // Quality metrics
        $completedOrders = Order::shippedPrint()->whereDate('shipped_at', $today)->count();
        $stats['quality_score'] = 98; // Placeholder - can be calculated from return/defect data
        
        return view('admin.manufacturing.dashboard', compact('stats', 'podProducts'));
    }

    /**
     * Capacity Planning - Manage production capacity
     */
    public function capacity()
    {
        $podProducts = Product::pod()
            ->withPositiveProductionCap()
            ->orderBy('name')
            ->get();
        
        $today = today();
        $dailyQuantities = $this->getTodayPodQuantities($today);
        
        // Calculate utilization for each product
        foreach ($podProducts as $product) {
            $dailyOrders = (int) ($dailyQuantities[$product->id] ?? 0);
            
            $product->daily_orders = $dailyOrders;
            $product->remaining_capacity = max(0, $product->production_cap - $dailyOrders);
            $product->utilization_percentage = $product->production_cap > 0 
                ? round(($dailyOrders / $product->production_cap) * 100) 
                : 0;
        }
        
        return view('admin.manufacturing.capacity', compact('podProducts'));
    }

    /**
     * Production Schedule - Calendar view
     */
    public function schedule()
    {
        // Get upcoming orders grouped by expected production date
        $upcomingOrders = Order::pendingPrint()
            ->where('status', 'processing')
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get();
        
        return view('admin.manufacturing.schedule', compact('upcomingOrders'));
    }

    /**
     * Quality Control - Track production quality
     */
    public function quality()
    {
        $stats = [
            'total_produced_week' => Order::printed()
                ->where('printed_at', '>=', now()->subWeek())
                ->count(),
            'total_shipped_week' => Order::shippedPrint()
                ->where('shipped_at', '>=', now()->subWeek())
                ->count(),
            'defect_rate' => 2, // Placeholder - would come from QC data
            'success_rate' => 98,
        ];
        
        $recentOrders = Order::printed()
            ->orderBy('printed_at', 'desc')
            ->take(20)
            ->get();
        
        return view('admin.manufacturing.quality', compact('stats', 'recentOrders'));
    }

    /**
     * Analytics & Reports - Production analytics
     */
    public function analytics(Request $request)
    {
        // Get month and year from request or use current
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        try {
            $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
        } catch (\Exception $e) {
            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();
            $month = $monthStart->month;
            $year = $monthStart->year;
        }

        // Get all printed orders for selected month
        $manufacturedOrders = Order::printed()
            ->whereBetween('printed_at', [$monthStart, $monthEnd])
            ->orderBy('printed_at', 'desc')
            ->get();

        $manufacturedProducts = [];
        $totalUnits = 0;

        foreach ($manufacturedOrders as $order) {
            $cart = json_decode($order->cart, true);
            if (isset($cart['items'])) {
                foreach ($cart['items'] as $item) {
                    $pid = $item['item']['id'];
                    $qty = $item['qty'];
                    $totalUnits += $qty;

                    if (!isset($manufacturedProducts[$pid])) {
                        $manufacturedProducts[$pid] = [
                            'id' => $pid,
                            'name' => $item['item']['name'],
                            'photo' => $item['item']['photo'],
                            'total_qty' => 0,
                            'last_produced' => $order->printed_at,
                            'orders' => []
                        ];
                    }

                    $manufacturedProducts[$pid]['total_qty'] += $qty;
                    $manufacturedProducts[$pid]['orders'][] = [
                        'order_number' => $order->order_number,
                        'qty' => $qty,
                        'date' => $order->printed_at->format('Y-m-d H:i')
                    ];
                    
                    // Keep track of the most recent production across all products
                    if ($order->printed_at > $manufacturedProducts[$pid]['last_produced']) {
                        $manufacturedProducts[$pid]['last_produced'] = $order->printed_at;
                    }
                }
            }
        }

        $periodInfo = [
            'month_name' => $monthStart->translatedFormat('F Y'),
            'month_num' => (int)$month,
            'year' => (int)$year,
            'total_orders' => $manufacturedOrders->count(),
            'total_units' => $totalUnits
        ];
        
        // Product popularity (last 30 days)
        $recentOrders = Order::where('created_at', '>=', now()->subDays(30))->get();
        $productCounts = [];
        foreach ($recentOrders as $order) {
            $cart = json_decode($order->cart, true);
            if (isset($cart['items'])) {
                foreach ($cart['items'] as $item) {
                    $pid = $item['item']['id'];
                    $productCounts[$pid] = ($productCounts[$pid] ?? 0) + $item['qty'];
                }
            }
        }
        arsort($productCounts);
        $topProductIds = array_slice(array_keys($productCounts), 0, 10);
        
        $topProducts = Product::whereIn('id', $topProductIds)->get();
        foreach ($topProducts as $product) {
            $product->orders_count = $productCounts[$product->id] ?? 0;
        }
        $topProducts = $topProducts->sortByDesc('orders_count');
        
        return view('admin.manufacturing.analytics', compact('manufacturedProducts', 'topProducts', 'periodInfo'));
    }

    /**
     * Manufacturing Queue - Orders waiting for manufacturing
     * Shows order details (size, color, product specs) for production team
     */
    public function queue()
    {
        // Get orders in manufacturing status
        $orders = Order::where('status', 'processing')
            ->manufacturing()
            ->orderBy('created_at', 'asc')
            ->paginate(20);
        
        // Parse cart details for each order
        foreach ($orders as $order) {
            $cart = json_decode($order->cart, true);
            $order->cart_items = $cart['items'] ?? [];
        }
        
        // Count stats
        $stats = [
            'in_manufacturing' => Order::where('status', 'processing')->manufacturing()->count(),
            'print_ready' => Order::where('status', 'processing')->printReady()->count(),
            'printing' => Order::where('status', 'processing')->printing()->count(),
        ];
        
        return view('admin.manufacturing.queue', compact('orders', 'stats'));
    }

    /**
     * Mark order as Print Ready - Manufacturing complete, send to printer
     */
    public function markPrintReady($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->print_status !== Order::PRINT_STATUS_MANUFACTURING) {
            return redirect()->back()->with('error', 'Order is not in manufacturing status');
        }
        
        $order->print_status = Order::PRINT_STATUS_PRINT_READY;
        $order->save();
        
        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' marked as Print Ready');
    }

    /**
     * Batch mark orders as Print Ready
     */
    public function batchMarkPrintReady(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        
        if (empty($orderIds)) {
            return response()->json(['error' => 'No orders selected'], 400);
        }
        
        $updated = Order::whereIn('id', $orderIds)
            ->where('print_status', Order::PRINT_STATUS_MANUFACTURING)
            ->update(['print_status' => Order::PRINT_STATUS_PRINT_READY]);
        
        return response()->json([
            'success' => true,
            'message' => $updated . ' orders marked as Print Ready'
        ]);
    }

    /**
     * View individual manufacturing order details
     */
    public function showOrder($id)
    {
        $order = Order::findOrFail($id);
        $cart = json_decode($order->cart, true);
        $order->cart_items = $cart['items'] ?? [];
        
        // Get products with full details in one query.
        $productIds = collect($order->cart_items)
            ->map(function ($item) {
                return $item['item']['id'] ?? null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Build manufacturing details per cart item.
        $productDetails = [];
        foreach ($order->cart_items as $item) {
            if (isset($item['item']['id'])) {
                $product = $products->get($item['item']['id']);
                if ($product) {
                    $printFile = $item['print_file'] ?? $product->print_file;
                    $productDetails[] = [
                        'product' => $product,
                        'qty' => $item['qty'] ?? 1,
                        'size' => $item['size'] ?? null,
                        'color' => $item['color'] ?? null,
                        'price' => $item['price'] ?? 0,
                        'print_file' => $printFile,
                        'print_file_url' => $this->resolvePrintFileUrl($printFile),
                    ];
                }
            }
        }
        $order->product_details = $productDetails;
        
        return view('admin.manufacturing.show', compact('order'));
    }

    /**
     * Resolve absolute URL for a print file stored in cart/product.
     */
    protected function resolvePrintFileUrl($printFile)
    {
        if (empty($printFile)) {
            return null;
        }

        if (filter_var($printFile, FILTER_VALIDATE_URL)) {
            return $printFile;
        }

        $normalized = ltrim($printFile, '/');
        $candidates = [
            ['disk' => public_path($normalized), 'url' => asset($normalized)],
            ['disk' => public_path('assets/files/designs/' . $normalized), 'url' => asset('assets/files/designs/' . $normalized)],
            ['disk' => public_path('assets/images/products/' . $normalized), 'url' => asset('assets/images/products/' . $normalized)],
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate['disk'])) {
                return $candidate['url'];
            }
        }

        return null;
    }

    /**
     * Aggregate today's POD quantities per product from processing orders.
     */
    protected function getTodayPodQuantities($date)
    {
        $quantities = [];
        $orders = Order::where('status', 'processing')
            ->whereDate('created_at', $date)
            ->get(['cart']);

        foreach ($orders as $order) {
            $cart = json_decode($order->cart, true);
            if (!isset($cart['items']) || !is_array($cart['items'])) {
                continue;
            }

            foreach ($cart['items'] as $item) {
                $pid = $item['item']['id'] ?? null;
                if (!$pid) {
                    continue;
                }
                $quantities[$pid] = ($quantities[$pid] ?? 0) + (int) ($item['qty'] ?? 1);
            }
        }

        return $quantities;
    }

    /**
     * Equipment Management - Manage printers/production equipment
     */
    public function printers()
    {
        // Get admin users with printer roles
        $printers = \App\Models\Admin::whereIn('role_id', [21, 22])
            ->get();
        
        // Get products with production capacity
        $equipment = Product::pod()
            ->withPositiveProductionCap()
            ->get();
        
        return view('admin.manufacturing.printers', compact('printers', 'equipment'));
    }

    /**
     * Update product capacity
     */
    public function updateCapacity(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'production_cap' => 'required|integer|min:0|max:1000'
        ]);
        
        $product->production_cap = $request->production_cap;
        $product->save();
        
        return redirect()->back()->with('success', 'Production capacity updated successfully');
    }
}
