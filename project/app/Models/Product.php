<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Generalsetting;
use App\Models\Currency;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use DB;

class Product extends Model
{
  protected static $hasIsPodColumn = null;
  protected static $hasProductionCapColumn = null;

  protected $fillable = ['user_id','category_id','product_type','affiliate_link','sku', 'subcategory_id', 'childcategory_id', 'attributes', 'name', 'photo', 'size','size_qty','size_price', 'color', 'details','price','previous_price','stock','policy','status', 'views','tags','featured','best','top','hot','latest','big','trending','sale','features','colors','product_condition','ship','meta_tag','meta_description','youtube','type','file','license','license_qty','link','platform','region','licence_type','measure','discount_date','is_discount','whole_sell_qty','whole_sell_discount','catalog_id','slug','language_id','flash_count','hot_count','new_count','sale_count','best_seller_count','popular_count','top_rated_count','big_save_count','trending_count','page_count','seller_product_count','wishlist_count','vendor_page_count','min_price','max_price','product_page','post_count','minimum_qty','preordered','language_id','color_all','size_all','stock_check', 'production_cap', 'is_pod', 'print_file', 'design_data', 'mockup_template_id'];

    public $selectable = ['id','user_id','name','slug','features','colors','thumbnail','price','previous_price','attributes','size','size_price','discount_date','color_all','size_all','stock_check','category_id','details'];

    public function scopeHome($query,$id)
    {
        return $query->where('status','=',1)->where('language_id',$id)->select($this->selectable)->latest('id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault();
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory')->withDefault();
    }

    public function childcategory()
    {
        return $this->belongsTo('App\Models\Childcategory')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }



    public function wishlist()
    {
        return $this->belongsTo('App\Models\Wishlist')->withDefault();
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report','product_id');
    }

    public function language()
    {
    	return $this->belongsTo('App\Models\Language','language_id')->withDefault();
    }

    public function IsSizeColor($value) {
        $sizes = array_unique($this->size);
        return in_array($value, $sizes);
    }

    public function checkVendor() {
        return $this->user_id != 0 ? '<small class="ml-2"> '.__("VENDOR").': <a href="'.route('admin-vendor-show',$this->user_id).'" target="_blank">'.$this->user->shop_name.'</a></small>' : '';
    }
    public function vendorPrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;
        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }

        return $price;
    }

    public function vendorSizePrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;
        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission;
        }
        if(!empty($this->size)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }

      if (!empty($attrArr)) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }

    // Attribute Section Ends
        return $price;
    }

    public  function setCurrency() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;
        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default','=',1)->first();
            });
        }
        $price = $price * $curr->value;
        $price = \PriceHelper::showPrice($price);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showPrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;

        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission;
        }

        if(!empty($this->size)){
            $price += $this->size_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if(!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                    $price += $attrVal['prices'][$optionKey];
                    // only the first price counts
                    break;
                    }

                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default','=',1)->first();
            });
        }

        $price = $price * $curr->value;
        $price = \PriceHelper::showPrice($price);

        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showPreviousPrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->previous_price;
        if(!$price){
            return '';
        }
        if($this->user_id != 0){
        $price = $this->previous_price + $gs->fixed_commission + ($this->previous_price/100) * $gs->percentage_commission ;
        }

        if(!empty($this->size)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }
      // dd($attrArr);
      if (!empty($attrArr)) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }

    // Attribute Section Ends

        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default','=',1)->first();
            });

        }

        $price = $price * $curr->value;
        $price = \PriceHelper::showPrice($price);

        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function convertPrice($price) {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default','=',1)->first();
            });
        }
        $price = $price * $curr->value;
        $price = \PriceHelper::showPrice($price);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function vendorConvertPrice($price) {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });

        $curr = Currency::where('is_default','=',1)->first();
        $price = $price * $curr->value;
        $price = \PriceHelper::showPrice($price);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showName() {
        $name = mb_strlen($this->name,'UTF-8') > 50 ? mb_substr($this->name,0,50,'UTF-8').'...' : $this->name;
        return $name;
    }

    public function emptyStock() {
        $stck = (string)$this->stock;
        if($stck == "0"){
            return true;
        }
        return false;
    }

    public static function showTags() {
        $tags = null;
        $tagz = '';
        $name = Product::where('status','=',1)->pluck('tags')->toArray();
        foreach($name as $nm)
        {
            if(!empty($nm))
            {
                foreach($nm as $n)
                {
                    $tagz .= $n.',';
                }
            }
        }
        $tags = array_unique(explode(',',$tagz));
        return $tags;
    }

    public function is_decimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    public function getSizeAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizeQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function offPercentage(){
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;

        $preprice = $this->previous_price;
        if(!$preprice){
            return '';
        }

        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission;

        $preprice = $this->previous_price + $gs->fixed_commission + ($this->previous_price/100) * $gs->percentage_commission ;
        }

        if(!empty($this->size)){
            $price += $this->size_price[0];
            $preprice += $this->size_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if(!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                    $price += $attrVal['prices'][$optionKey];
                    // only the first price counts
                    $preprice += $attrVal['prices'][$optionKey];
                    break;
                    }

                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default','=',1)->first();
            });
        }

        $price = $price * $curr->value;
        $preprice = $preprice * $curr->value;
        $Percentage=(($preprice-$price)*100)/$preprice;
        return $Percentage;

    }

    // ==================== POD-SPECIFIC METHODS ====================

    public function mockupTemplate()
    {
        return $this->belongsTo(MockupTemplate::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function isAvailable($quantity = 1)
    {
        if ($this->is_pod) {
            $todayProduction = PrintJob::where('product_id', $this->id)
                ->whereDate('created_at', today())
                ->sum('quantity');
            $remainingCapacity = $this->production_cap - $todayProduction;
            return $remainingCapacity >= $quantity;
        }
        
        if ($this->stock_check == 1) {
            return $this->stock >= $quantity;
        }

        return true;
    }

    public function getTodayProductionCount()
    {
        return PrintJob::where('product_id', $this->id)
            ->whereDate('created_at', today())
            ->sum('quantity');
    }

    public function getRemainingCapacityAttribute()
    {
        if (!$this->is_pod) {
            return $this->stock;
        }
        return max(0, $this->production_cap - $this->getTodayProductionCount());
    }

    public function getCapacityUtilizationAttribute()
    {
        if (!$this->is_pod || $this->production_cap == 0) {
            return 0;
        }
        $used = $this->getTodayProductionCount();
        return round(($used / $this->production_cap) * 100, 2);
    }

    public function createPrintJob($orderId, $quantity = 1, $qualityTier = null, $designFile = null)
    {
        $tier = $qualityTier ?? $this->quality_tier ?? 'standard';
        $priority = match($tier) {
            'deluxe' => 1,
            'premium' => 2,
            default => 3,
        };

        return PrintJob::create([
            'order_id' => $orderId,
            'product_id' => $this->id,
            'design_file' => $designFile ?? $this->print_file,
            'mockup_preview' => $this->photo,
            'quantity' => $quantity,
            'quality_tier' => $tier,
            'status' => 'queued',
            'estimated_time_minutes' => ($this->print_time_minutes ?? 30) * $quantity,
            'priority' => $priority
        ]);
    }

    public function scopePod($query)
    {
        if (!static::hasIsPodColumn()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('is_pod', 1);
    }

    public function getMockupStyleAttribute()
    {
        if (!$this->is_pod || !$this->mockupTemplate) return null;

        $template = $this->mockupTemplate;
        $path = public_path('assets/images/mockups/' . $template->image);
        
        if (!file_exists($path)) return null;

        list($width, $height) = getimagesize($path);

        // Logic matching admin CSS: max-width: 500px; max-height: 500px;
        $scale = min(1, 500 / $width, 500 / $height);
        
        $dispW = $width * $scale;
        $dispH = $height * $scale;

        $left = ($template->design_x / $dispW) * 100;
        $top = ($template->design_y / $dispH) * 100;
        $w = ($template->design_width / $dispW) * 100;
        $h = ($template->design_height / $dispH) * 100;

        return "top: {$top}%; left: {$left}%; width: {$w}%; height: {$h}%;";
    }

    public function scopeTraditional($query)
    {
        if (!static::hasIsPodColumn()) {
            return $query;
        }

        return $query->where('is_pod', 0);
    }

    public function scopeWithPositiveProductionCap($query)
    {
        if (!static::hasProductionCapColumn()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('production_cap', '>', 0);
    }

    public static function hasIsPodColumn()
    {
        if (static::$hasIsPodColumn === null) {
            $table = (new static())->getTable();
            static::$hasIsPodColumn = Schema::hasTable($table) && Schema::hasColumn($table, 'is_pod');
        }

        return static::$hasIsPodColumn;
    }

    public static function hasProductionCapColumn()
    {
        if (static::$hasProductionCapColumn === null) {
            $table = (new static())->getTable();
            static::$hasProductionCapColumn = Schema::hasTable($table) && Schema::hasColumn($table, 'production_cap');
        }

        return static::$hasProductionCapColumn;
    }
}
