<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catering_status_id',
        'delivery_date',
        'closest_location',
        'pickup_location',
        'pickup_date',
        'pickup_first_name',
        'catering',
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
        'pickup_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
