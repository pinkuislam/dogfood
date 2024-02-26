<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    protected $table = 'product_materials';

    protected $fillable = [
        'product_id','material_id'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'material_id' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'material_id');
    }
}
