<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockupTemplate extends Model
{
    protected $fillable = [
        'name',
        'product_type',
        'style',
        'color',
        'image',
        'view_side',
        'design_x',
        'design_y',
        'design_width',
        'design_height',
        'status'
    ];

    /**
     * Product types available for mockups
     */
    public static $productTypes = [
        'tshirt' => 'T-Shirt',
        'hoodie' => 'Hoodie',
        'sweatshirt' => 'Sweatshirt',
        'mug' => 'Mug',
        'phonecase' => 'Phone Case',
        'totebag' => 'Tote Bag',
        'poster' => 'Poster',
        'pillow' => 'Pillow'
    ];

    /**
     * Style variants
     */
    public static $styles = [
        'regular' => 'Regular Fit',
        'oversized' => 'Oversized',
        'slim_fit' => 'Slim Fit',
        'cropped' => 'Cropped',
        'standard' => 'Standard'
    ];

    /**
     * Common colors
     */
    public static $colors = [
        'white' => 'White',
        'black' => 'Black',
        'navy' => 'Navy Blue',
        'gray' => 'Gray',
        'red' => 'Red',
        'green' => 'Green',
        'blue' => 'Blue',
        'yellow' => 'Yellow',
        'pink' => 'Pink',
        'purple' => 'Purple'
    ];

    /**
     * Scope for active templates only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope by product type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('assets/images/mockups/' . $this->image);
    }

    /**
     * Get design area configuration
     */
    public function getDesignAreaAttribute()
    {
        return [
            'x' => $this->design_x,
            'y' => $this->design_y,
            'maxWidth' => $this->design_width,
            'maxHeight' => $this->design_height
        ];
    }

    /**
     * Get formatted product type name
     */
    public function getProductTypeNameAttribute()
    {
        return self::$productTypes[$this->product_type] ?? $this->product_type;
    }

    /**
     * Get formatted style name
     */
    public function getStyleNameAttribute()
    {
        return self::$styles[$this->style] ?? $this->style;
    }

    /**
     * Get formatted color name
     */
    public function getColorNameAttribute()
    {
        return self::$colors[$this->color] ?? ucfirst($this->color);
    }
}
