<?php

use App\Livewire\PlaceCateringOrder;
use App\Models\User;
use App\Models\CateringProduct;
use App\Models\Location;
use App\Enums\CateringOrderTimes;
use Illuminate\Support\Facades\Mail;
use App\Mail\CateringOrderPlacedInt;

test('order page working', function () {
    $response = $this->get('/order');

    $response->assertStatus(200);
});

it('submits the form successfully', function () {
    Mail::fake();

    $user = User::factory()->create();
    $product = CateringProduct::factory()->create();
    $location = Location::factory()->create();

    $formData = [
        'customerDetails' => [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
        ],
        'orderDetails' => [
            'delivery' => true,
            'setup' => true,
            'closest_location' => $location->id,
            'order_date' => now()->addDays(2)->format('m/d/Y'),
            'order_time' => CateringOrderTimes::values()[0],
            'number_people' => 5,
            'pickup_first_name' => 'John',
            'notes' => 'Some notes',
        ],
        'orderProducts' => [
            [
                'sku' => $product->sku,
                'quantity' => 1,
            ],
        ]
    ];

    // Act as the user
    $this->actingAs($user);

    Livewire::test(PlaceCateringOrder::class)
        ->set('customerDetails', $formData['customerDetails'])
        ->set('orderDetails', $formData['orderDetails'])
        ->set('orderProducts', $formData['orderProducts'])
        ->call('submitAndCreateOrder');

    $this->assertDatabaseHas('catering_orders', [
        'user_id' => $user->id,
        'delivery' => 1,
        'setup' => 1,
        'closest_location' => $location->id,
        'order_date' => now()->addDays(2)->format('Y-m-d'),
        'order_time' => CateringOrderTimes::values()[0],
        'number_people' => 5,
        'pickup_first_name' => 'John',
        'notes' => 'Some notes',
    ]);

    // Assert that the products were attached to the order
    $this->assertDatabaseHas('catering_order_products', [
        'product_id' => $product->id,
    ]);


});
