<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringOrderProduct extends Model
{
    public function details()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
