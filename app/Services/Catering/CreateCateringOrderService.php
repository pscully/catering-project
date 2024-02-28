<?php

namespace App\Services\Catering;

use App\Models\CateringOrder;
use App\Models\DeliveryLocation;
use App\Models\User;

class CreateCateringOrderService
{
    public function run(User $user, array $orderDetails): CateringOrder
    {
        $order = new CateringOrder([
            'delivery' => $orderDetails['delivery'],
            'setup' => $orderDetails['setup'],
            'order_date' => $orderDetails['order_date'],
            'closest_location' => $orderDetails['closest_location'],
            'pickup_first_name' => $orderDetails['pickup_first_name'],
            'notes' => $orderDetails['notes'],
            'number_people' => $orderDetails['number_people'],
            'order_time' => $orderDetails['order_time'],
        ]);

        $user->cateringOrders()->save($order);

        if ($orderDetails['delivery'] === "1")
        {
            $location = new DeliveryLocation([
                'street' => $orderDetails['delivery_street'],
                'apartment_suite' => $orderDetails['apartment_suite'],
                'city' => $orderDetails['delivery_city'],
                'state' => $orderDetails['delivery_state'],
                'zip' => $orderDetails['delivery_zip'],
                'is_business' => $orderDetails['is_business'],
                'business_name' => $orderDetails['business_name'],
            ]);

            $addressString = $orderDetails['delivery_street']. ', #'. $orderDetails['apartment_suite']. ', '. $orderDetails['delivery_city']. ', '. $orderDetails['delivery_state'].' '. $orderDetails['delivery_zip'];

            $coordinates = $location->getCoordinates($addressString);
            $location->setCoordinatesAttribute($coordinates);

            $location->order_id = $order->id;

            $location->save();
        }

        return $order;

    }
}
