<?php

namespace App\Models;

use App\Mail\CateringOrderPlacedInt;
use App\Services\Catering\AttachCateringOrderProductsService;
use App\Services\Catering\CalculateCateringOrderDeliveryFeeService;
use App\Services\Catering\CalculateCateringOrderTotalService;
use App\Services\Catering\CalculateDeliveryDistanceMilesService;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class CateringOrder extends Model
{
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
        'delivery_fee',
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cateringStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CateringStatus::class);
    }

    public function getOrderStatusAttribute()
    {
        return $this->cateringStatus->name;
    }

    public function getOrderLocationAttribute()
    {
        return $this->location->name;
    }

    public function cateringOrderProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CateringOrderProduct::class);
    }

    public function deliveryLocation(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(DeliveryLocation::class, 'addressable');
    }

    public function attachProductsToThisOrder(array $orderProducts, $attacher): void
    {
        $attacher->run($this, $orderProducts);
    }

    public function calculateThisOrderTotal(array $orderProducts, $calculator): void
    {
        $this->total = $calculator->run($orderProducts);
    }

    public function sendOrderConfirmationEmail(): void
    {
        Mail::to($this->user->email)->send(new CateringOrderPlacedInt($this));
        $this->notified_at = now();
    }

    public function calculateDeliveryFee($deliveryFeeCalculator,  $milesCalculator): void
    {
        $deliveryLocation = DeliveryLocation::where('order_id', $this->id)->first();
        $selectedStore = Location::where('id', $this->closest_location)->first();

        $miles = $milesCalculator->run($selectedStore, $deliveryLocation->latitude, $deliveryLocation->longitude);
        $this->delivery_fee = $deliveryFeeCalculator->run($this->total, $miles, $this->setup);
    }

    public function attachProductsAndFinalize($orderProducts, $isDelivery, $attacher, $calculator, $deliveryFeeCalculator, $milesCalculator): void
    {
        $this->attachProductsToThisOrder($orderProducts, $attacher);
        $this->calculateThisOrderTotal($orderProducts, $calculator);


        if ($isDelivery === "1") {
            $this->calculateDeliveryFee($deliveryFeeCalculator, $milesCalculator );
        }

        $this->save();
        $this->sendOrderConfirmationEmail();

    }

    public function acceptOrder()
    {
        dd("Accepting Order");
    }
}
