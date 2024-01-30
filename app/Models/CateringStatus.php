<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    const NEW_ID        = 1;
    const IN_PROGRESS_ID   = 2;
    const DECLINED_ID  = 3;
    const COMPLETED_ID  = 4;
    const DELIVERED_ID  = 5;
    const REFUNDED_ID   = 6;
    const FULFILLED_ID  = 7;
}
