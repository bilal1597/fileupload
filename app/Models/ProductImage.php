<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'imsges_product';
    protected $fillable = [
        'product_id',
        'image'
    ];
}
