<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'date', 'Ref', 'supplier_id', 'warehouse_id', 'GrandTotal',
        'discount', 'shipping', 'status', 'notes', 'TaxNet', 'tax_rate', 'paid_amount',
        'payment_status', 'created_at', 'updated_at', 'deleted_at','discount_type','discount_percent_total'
    ];

    protected $casts = [
        'supplier_id' => 'integer',
        'warehouse_id' => 'integer',
        'GrandTotal' => 'double',
        'discount' => 'double',
        'discount_percent_total' => 'double',
        'shipping' => 'double',
        'TaxNet' => 'double',
        'tax_rate' => 'double',
        'paid_amount' => 'double',
    ];

    public function details()
    {
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
    }

    public function facture()
    {
        return $this->hasMany('App\Models\PaymentPurchase');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
