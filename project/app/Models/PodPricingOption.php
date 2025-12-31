<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodPricingOption extends Model
{
    protected $fillable = [
        'category',
        'name',
        'value',
        'price',
        'description',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Available categories
     */
    public static $categories = [
        'print_quality' => 'Print Quality',
        'clothing_quality' => 'Clothing Quality',
        'production_speed' => 'Production Speed',
        'tag_option' => 'Tag Options',
        'packaging' => 'Packaging'
    ];

    /**
     * Scope for active options
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get options grouped by category
     */
    public static function getGrouped()
    {
        return self::active()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');
    }
}
