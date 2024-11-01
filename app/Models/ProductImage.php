<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class ProductImage extends Model
{
    protected $table = 'imsges_product';
    protected $fillable = [
        'product_id',
        'images'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
