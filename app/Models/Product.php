<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProductImage;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'details',
        'image',
        'file',
        'price',
        'images'
    ];
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
