<?php

use App\Models\User;
use App\Models\CateringProduct;
use App\Models\Location;
use App\Enums\CateringOrderTimes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\CateringOrderPlacedInt;

uses(RefreshDatabase::class);

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
            $product->sku => 2,
        ],
    ];

    Livewire::actingAs($user)
        ->test(PlaceCateringOrder::class)
        ->set('customerDetails', $formData['customerDetails'])
        ->set('orderDetails', $formData['orderDetails'])
        ->set('orderProducts', $formData['orderProducts'])
        ->call('placeCateringOrderSubmit')
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('catering_orders', [
        'user_id' => $user->id,
        'delivery' => true,
        'setup' => true,
        'closest_location' => $location->id,
        'order_date' => now()->addDays(2)->format('Y-m-d'),
        'order_time' => CateringOrderTimes::values()[0],
        'number_people' => 5,
        'pickup_first_name' => 'John',
        'notes' => 'Some notes',
    ]);

    $this->assertDatabaseHas('catering_order_products', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    Mail::assertSent(CateringOrderPlacedInt::class);
});
