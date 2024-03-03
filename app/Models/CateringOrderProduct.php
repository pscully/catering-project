<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringOrderProduct extends Model
{

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CateringProduct::class, 'id', 'product_id');
    }
}
