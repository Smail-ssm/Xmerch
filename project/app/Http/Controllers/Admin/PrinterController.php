<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class PrinterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Printer Dashboard - Overview of print queue
     */
    public function dashboard()
    {
        // Get print-ready orders (completed manufacturing, waiting for printer)
        $printReadyOrders = Order::printReady()->where('status', 'processing')->get();
        
        $stats = [
            'in_manufacturing' => Order::manufacturing()->where('status', 'processing')->count(),
            'pending' => Order::pendingPrint()->where('status', 'processing')->count(),
            'eligible' => $printReadyOrders->count(),
            'print_ready' => $printReadyOrders->count(),
            'printing' => Order::printing()->count(),
            'printed' => Order::printed()->count(),
            'shipped_today' => Order::shippedPrint()->whereDate('shipped_at', today())->count(),
        ];

        // Show print_ready and printing orders
        $recentOrders = Order::where('status', 'processing')
            ->whereIn('print_status', ['print_ready', 'printing'])
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        return view('admin.printer.dashboard', compact('stats', 'recentOrders'));
    }

    /**
     * Print Queue - All pending print orders
     */
    public function queue()
    {
        // Show orders that are either in manufacturing or ready for printer
        $orders = Order::where('status', 'processing')
            ->whereIn('print_status', [Order::PRINT_STATUS_MANUFACTURING, Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.printer.queue', compact('orders'));
    }

    /**
     * Currently Printing
     */
    public function printing()
    {
        $orders = Order::printing()
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.printer.printing', compact('orders'));
    }

    /**
     * Ready to Ship (printed)
     */
    public function readyToShip()
    {
        $orders = Order::printed()
            ->orderBy('printed_at', 'desc')
            ->paginate(20);

        return view('admin.printer.ready-to-ship', compact('orders'));
    }

    /**
     * Shipped Orders
     */
    public function shipped()
    {
        $orders = Order::shippedPrint()
            ->orderBy('shipped_at', 'desc')
            ->paginate(20);

        return view('admin.printer.shipped', compact('orders'));
    }

    /**
     * View print job details
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $cart = json_decode($order->cart, true);

        return view('admin.printer.show', compact('order', 'cart'));
    }

    /**
     * Start printing an order
     */
    public function startPrint($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'processing') {
            return redirect()->back()->with('error', 'Order is not in processing state');
        }

        if (!in_array($order->print_status, [Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING])) {
            return redirect()->back()->with('error', 'Order must be Print Ready before starting');
        }

        $printerId = Auth::guard('admin')->id();

        $order->print_status = Order::PRINT_STATUS_PRINTING;
        $order->printer_id = $printerId;
        $order->save();

        // Keep line-item print jobs aligned when starting at order level.
        PrintJob::where('order_id', $order->id)
            ->where('status', PrintJob::STATUS_QUEUED)
            ->update([
                'status' => PrintJob::STATUS_PRINTING,
                'printer_id' => $printerId,
                'started_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Order marked as printing');
    }

    /**
     * Mark order as printed
     */
    public function markPrinted($id)
    {
        $order = Order::findOrFail($id);

        if ($order->print_status !== Order::PRINT_STATUS_PRINTING) {
            return redirect()->back()->with('error', 'Only printing orders can be marked as printed');
        }

        $now = now();

        $order->print_status = Order::PRINT_STATUS_PRINTED;
        $order->printed_at = $now;
        $order->save();

        // If staff closes the order at this level, close remaining line-item jobs too.
        $jobsToComplete = PrintJob::where('order_id', $order->id)
            ->whereIn('status', [
                PrintJob::STATUS_QUEUED,
                PrintJob::STATUS_PRINTING,
                PrintJob::STATUS_ON_HOLD,
                PrintJob::STATUS_FAILED,
            ])
            ->get();

        foreach ($jobsToComplete as $job) {
            $actualTime = $job->started_at ? $now->diffInMinutes($job->started_at) : null;
            $job->update([
                'status' => PrintJob::STATUS_COMPLETED,
                'completed_at' => $now,
                'actual_time_minutes' => $actualTime,
            ]);
        }

        return redirect()->back()->with('success', 'Order marked as printed')
                         ->with('auto_print_label', route('admin-printer-label', $id));
    }

    /**
     * Mark order as shipped
     */
    public function markShipped(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->print_status !== Order::PRINT_STATUS_PRINTED) {
            return redirect()->back()->with('error', 'Order must be printed before shipping');
        }

        $order->print_status = Order::PRINT_STATUS_SHIPPED;
        $order->shipped_at = now();
        $order->status = 'completed';
        $order->save();

        return redirect()->back()->with('success', 'Order marked as shipped');
    }

    public function batchStartPrint(Request $request)
    {
        $ids = $request->input('order_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        
        if (!empty($ids)) {
            $eligibleIds = Order::whereIn('id', $ids)
                ->where('status', 'processing')
                ->whereIn('print_status', [Order::PRINT_STATUS_PRINT_READY, Order::PRINT_STATUS_PENDING])
                ->pluck('id')
                ->all();

            $updated = Order::whereIn('id', $eligibleIds)
                ->update([
                'print_status' => Order::PRINT_STATUS_PRINTING,
                'printer_id' => Auth::guard('admin')->id()
            ]);

            if ($updated > 0) {
                PrintJob::whereIn('order_id', $eligibleIds)
                    ->where('status', PrintJob::STATUS_QUEUED)
                    ->update([
                        'status' => PrintJob::STATUS_PRINTING,
                        'printer_id' => Auth::guard('admin')->id(),
                        'started_at' => now(),
                    ]);

                return redirect()->back()->with('success', $updated . ' orders marked as printing');
            }

            return redirect()->back()->with('error', 'No eligible orders selected for printing');
        }

        return redirect()->back()->with('error', 'No orders selected');
    }

    public function batchMarkPrinted(Request $request)
    {
        $ids = $request->input('order_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        
        if (!empty($ids)) {
            $eligibleIds = Order::whereIn('id', $ids)
                ->where('print_status', Order::PRINT_STATUS_PRINTING)
                ->pluck('id')
                ->all();

            $updated = Order::whereIn('id', $eligibleIds)
                ->update([
                'print_status' => Order::PRINT_STATUS_PRINTED,
                'printed_at' => now()
            ]);

            if ($updated > 0) {
                PrintJob::whereIn('order_id', $eligibleIds)
                    ->whereIn('status', [
                        PrintJob::STATUS_QUEUED,
                        PrintJob::STATUS_PRINTING,
                        PrintJob::STATUS_ON_HOLD,
                        PrintJob::STATUS_FAILED,
                    ])
                    ->update([
                        'status' => PrintJob::STATUS_COMPLETED,
                        'completed_at' => now(),
                    ]);

                return redirect()->back()->with('success', $updated . ' orders marked as printed');
            }

            return redirect()->back()->with('error', 'No printing orders selected');
        }

        return redirect()->back()->with('error', 'No orders selected');
    }

    /**
     * Get print file for an order item
     */
    public function printFile($id)
    {
        $order = Order::findOrFail($id);
        
        $path = $order->print_file_path;
        if ($path && file_exists($path)) {
            return response()->file($path);
        }

        return abort(404, 'Print file not found');
    }

    /**
     * Generate shipping label for an order
     */
    public function shippingLabel($id)
    {
        $order = Order::findOrFail($id);
        $gs = \App\Models\Generalsetting::findOrFail(1);

        return view('admin.printer.label', compact('order', 'gs'));
    }

    /**
     * Manage Manufacturing Accounts
     */
    public function accounts()
    {
        $printerRoles = \App\Models\Role::where('section', 'like', '%print_production%')
            ->orWhere('section', 'like', '%manufacturing%')
            ->pluck('id');

        $staffs = \App\Models\Admin::whereIn('role_id', $printerRoles)->get();
        $roles = \App\Models\Role::all();

        return view('admin.printer.accounts', compact('staffs', 'roles'));
    }
}
