<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\ProductImage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'material',
        'hallmark',
        'weight',
        'stone',
        'price',
        'stock',
        'popularity',
        'is_new',
        'is_active'
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'float',
        'weight' => 'float'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function mainImage(): string
{
    $image = $this->images()->first();
    
    if ($image && file_exists(public_path($image->path))) {
        return $image->path;
    }
    
    return 'images/common/no-image.jpg';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Проверка наличия на складе
    public function inStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}

public function approvedReviews()
{
    return $this->hasMany(Review::class)->where('is_approved', true);
}

public function getAverageRatingAttribute()
{
    return $this->approvedReviews()->avg('rating') ?: 0;
}

public function getReviewsCountAttribute()
{
    return $this->approvedReviews()->count();
}
}