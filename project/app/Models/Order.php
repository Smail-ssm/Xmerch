<?php

namespace App\Models;
use DB;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Per-request cache for pending POD quantities keyed by product id.
     */
    protected static $pendingPodQtyCache = null;
    /**
     * Print status constants for POD
     * Flow: manufacturing -> print_ready -> printing -> printed -> shipped
     */
    const PRINT_STATUS_MANUFACTURING = 'manufacturing';
    const PRINT_STATUS_PRINT_READY = 'print_ready';
    const PRINT_STATUS_PENDING = 'pending_print'; // Legacy - kept for compatibility
    const PRINT_STATUS_PRINTING = 'printing';
    const PRINT_STATUS_PRINTED = 'printed';
    const PRINT_STATUS_SHIPPED = 'shipped';

    public static $printStatuses = [
        'manufacturing' => 'In Manufacturing',
        'print_ready' => 'Print Ready',
        'pending_print' => 'Pending Print', // Legacy
        'printing' => 'Printing',
        'printed' => 'Printed',
        'shipped' => 'Shipped'
    ];

	protected $fillable = ['user_id', 'cart', 'method','shipping', 'pickup_location', 'totalQty', 'pay_amount', 'txnid', 'charge_id', 'order_number', 'payment_status', 'customer_name', 'customer_email', 'customer_phone', 'customer_address', 'customer_city', 'customer_zip','customer_state', 'customer_country','shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_zip','shipping_state','shipping_country', 'order_note','coupon_code','coupon_discount','status','affilate_user','affilate_charge','currency_sign','currency_name','currency_value','shipping_cost','packing_cost','tax','tax_location','dp','pay_id','vendor_shipping_id','vendor_packing_id','wallet_price','shipping_title','packing_title','affilate_users','commission', 'print_status', 'design_data', 'design_image', 'printed_at', 'shipped_at', 'printer_id'];

    protected $casts = [
        'printed_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    /**
     * Scope for orders in manufacturing
     */
    public function scopeManufacturing($query)
    {
        return $query->where('print_status', self::PRINT_STATUS_MANUFACTURING);
    }

    /**
     * Scope for print ready orders (ready for printer)
     */
    public function scopePrintReady($query)
    {
        return $query->where('print_status', self::PRINT_STATUS_PRINT_READY);
    }

    /**
     * Scope for pending print orders (legacy)
     */
    public function scopePendingPrint($query)
    {
        return $query->whereIn('print_status', [self::PRINT_STATUS_MANUFACTURING, self::PRINT_STATUS_PENDING]);
    }

    /**
     * Scope for printing orders
     */
    public function scopePrinting($query)
    {
        return $query->where('print_status', self::PRINT_STATUS_PRINTING);
    }

    /**
     * Scope for printed orders
     */
    public function scopePrinted($query)
    {
        return $query->where('print_status', self::PRINT_STATUS_PRINTED);
    }

    /**
     * Scope for shipped orders
     */
    public function scopeShippedPrint($query)
    {
        return $query->where('print_status', self::PRINT_STATUS_SHIPPED);
    }

    /**
     * Get print status label
     */
    public function getPrintStatusLabelAttribute()
    {
        return self::$printStatuses[$this->print_status] ?? $this->print_status;
    }

    /**
     * Get printer staff
     */
    public function printer()
    {
        return $this->belongsTo('App\Models\Admin', 'printer_id');
    }

    /**
     * Get all product IDs in this order
     */
    public function getProductIds()
    {
        $cart = json_decode($this->cart, true);
        $ids = [];
        if (isset($cart['items'])) {
            foreach ($cart['items'] as $item) {
                if (isset($item['item']['id'])) {
                    $ids[] = $item['item']['id'];
                }
            }
        }
        return array_unique($ids);
    }

    /**
     * Check if the order is eligible for production based on product caps
     */
    public function isEligibleForProduction()
    {
        $productIds = $this->getProductIds();

        if (empty($productIds)) {
            return false;
        }

        if (!Product::hasIsPodColumn() || !Product::hasProductionCapColumn()) {
            return false;
        }

        $podProducts = Product::whereIn('id', $productIds)
            ->pod()
            ->withPositiveProductionCap()
            ->get(['id', 'production_cap']);

        if ($podProducts->isEmpty()) {
            return false;
        }

        $pendingQuantities = $this->getPendingPodQuantities();

        foreach ($podProducts as $product) {
            $totalPending = (int) ($pendingQuantities[$product->id] ?? 0);

            // Keep legacy semantics used by current UI: highlight when capacity is reached.
            if ($totalPending >= (int) $product->production_cap) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build and cache pending POD quantities once per request.
     */
    protected function getPendingPodQuantities()
    {
        if (is_array(self::$pendingPodQtyCache)) {
            return self::$pendingPodQtyCache;
        }

        $quantities = [];
        $pendingOrders = self::where('status', 'processing')
            ->whereIn('print_status', [self::PRINT_STATUS_MANUFACTURING, self::PRINT_STATUS_PENDING])
            ->get(['cart']);

        foreach ($pendingOrders as $pendingOrder) {
            $pCart = json_decode($pendingOrder->cart, true);
            if (!isset($pCart['items']) || !is_array($pCart['items'])) {
                continue;
            }

            foreach ($pCart['items'] as $item) {
                $pid = $item['item']['id'] ?? null;
                if (!$pid) {
                    continue;
                }

                $quantities[$pid] = ($quantities[$pid] ?? 0) + (int) ($item['qty'] ?? 1);
            }
        }

        self::$pendingPodQtyCache = $quantities;

        return self::$pendingPodQtyCache;
    }

    /**
     * Get the active print file for this order (from order or products)
     */
    public function getPrintFileAttribute()
    {
        // If the order has its own high-res design image, use it
        if ($this->design_image) {
            return asset($this->design_image);
        }

        // Otherwise, look for the first POD product in the cart
        $cart = json_decode($this->cart, true);
        if (isset($cart['items'])) {
            foreach ($cart['items'] as $item) {
                if (isset($item['item']['id'])) {
                    $product = \App\Models\Product::find($item['item']['id']);
                    if ($product && $product->is_pod && $product->print_file) {
                        $newPath = public_path('assets/files/designs/' . $product->print_file);
                        if (file_exists($newPath)) {
                            return asset('assets/files/designs/' . $product->print_file);
                        }

                        // Backward compatibility for legacy vendor exports.
                        $legacyPath = public_path('assets/images/products/' . $product->print_file);
                        if (file_exists($legacyPath)) {
                            return asset('assets/images/products/' . $product->print_file);
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get the absolute path to the print file
     */
    public function getPrintFilePathAttribute()
    {
        if ($this->design_image) {
            return public_path($this->design_image);
        }

        $cart = json_decode($this->cart, true);
        if (isset($cart['items'])) {
            foreach ($cart['items'] as $item) {
                if (isset($item['item']['id'])) {
                    $product = \App\Models\Product::find($item['item']['id']);
                    if ($product && $product->is_pod && $product->print_file) {
                        $newPath = public_path('assets/files/designs/' . $product->print_file);
                        if (file_exists($newPath)) {
                            return $newPath;
                        }

                        // Backward compatibility for legacy vendor exports.
                        $legacyPath = public_path('assets/images/products/' . $product->print_file);
                        if (file_exists($legacyPath)) {
                            return $legacyPath;
                        }
                    }
                }
            }
        }

        return null;
    }

    public function vendororders()
    {
        return $this->hasMany('App\Models\VendorOrder','order_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification','order_id');
    }

    public function tracks()
    {
        return $this->hasMany('App\Models\OrderTrack','order_id');
    }

    public static function getShipData($cart,$language_id)
    {
        $vendor_shipping_id = 0;
        $user = array();
        foreach ($cart->items as $prod) {
                $user[] = $prod['item']['user_id'];
        }
        $users = array_unique($user);
        if(count($users) == 1)
        {
            $shipping_data  = DB::table('shippings')->whereLanguageId($language_id)->whereUserId($users[0])->get();
            if(count($shipping_data) == 0){
                $shipping_data  = DB::table('shippings')->whereLanguageId($language_id)->whereUserId(0)->get();
            }
            else{
                $vendor_shipping_id = $users[0];
            }
        }
        else {
            $shipping_data  = DB::table('shippings')->whereLanguageId($language_id)->whereUserId(0)->get();
        }
        $data['shipping_data'] = $shipping_data;
        $data['vendor_shipping_id'] = $vendor_shipping_id;
        return $data; 
    }

    public static function getPackingData($cart,$language_id)
    {
        $vendor_packing_id = 0;
        $user = array();
        foreach ($cart->items as $prod) {
                $user[] = $prod['item']['user_id'];
        }
        $users = array_unique($user);
        if(count($users) == 1)
        {
            $package_data  = DB::table('packages')->whereLanguageId($language_id)->whereUserId($users[0])->get();

            if(count($package_data) == 0){
                $package_data  = DB::table('packages')->whereLanguageId($language_id)->whereUserId(0)->get();
            }
            else{
                $vendor_packing_id = $users[0];
            }  
        }
        else {
            $package_data  = DB::table('packages')->whereLanguageId($language_id)->whereUserId(0)->get();
        }
        $data['package_data'] = $package_data;
        $data['vendor_packing_id'] = $vendor_packing_id;
        return $data; 
    }
}

