<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'discount',
        'rating',
        'reviews',
        'main_image',
        'gallery',
        'colors',
        'breadcrumbs',
        'badge',
        'category_id',
        'stock'
    ];

    protected $casts = [
        'gallery' => 'array',
        'colors' => 'array',
        'breadcrumbs' => 'array',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Compatibility methods for existing codebase
     */
    public static function getByCategory($category)
    {
        return self::whereJsonContains('breadcrumbs', $category)
            ->orWhereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            })->get();
    }

    public static function getNew()
    {
        return self::where('badge', 'NEW')->get();
    }

    public static function getPromo()
    {
        return self::whereNotNull('old_price')->get();
    }

    public static function search($query)
    {
        return self::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();
    }

    /**
     * Accessors to handle image URLs
     */
    public function getMainImageAttribute($value)
    {
        if (!$value) {
            return 'https://via.placeholder.com/300';
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return asset('storage/' . $value);
    }

    public function getGalleryAttribute($value)
    {
        $gallery = is_array($value) ? $value : (json_decode($value, true) ?: []);
        
        return array_map(function($img) {
            if (str_starts_with($img, 'http')) {
                return $img;
            }
            return asset('storage/' . $img);
        }, $gallery);
    }

    /**
     * Get the reviews for the product.
     */
    public function allReviews()
    {
        return $this->hasMany(Review::class);
    }
}
