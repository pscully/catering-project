<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CateringOrder extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'catering_status_id',
        'order_date',
        'order_time',
        'closest_location',
        'pickup_first_name',
        'notes',
        'number_people',
        'delivery',
        'setup',
        'coffee_type',
        'charge_id',
        'pp_capture_id',
        'total',
        'refunded_sum',
        'image_filename',
        'notified_at',
        'status_updated_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cateringStatus()
    {
        return $this->belongsTo(CateringStatus::class);
    }

    public function getOrderStatusAttribute()
    {
        return $this->cateringStatus->name;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'closest_location');
    }

    public function getOrderLocationAttribute()
    {
        return $this->location->name;
    }

    public function cateringOrderProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CateringOrderProduct::class);
    }

    public function deliveryAddress(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(DeliveryAddress::class, 'addressable');
    }
}
