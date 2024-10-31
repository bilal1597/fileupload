<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductImage extends Model
{
    // public function productImages(): HasMany
    // {
    //     return $this->hasMany(ProductImage::class, 'product_id');
    // }
    protected $table = 'imsges_product';
    protected $fillable = [
        'product_id',
        'image'
    ];
}
