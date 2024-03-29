<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id','code', 'name', 'cost','price'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'cost' => 'double',
        'price' => 'double',
    ];

}
