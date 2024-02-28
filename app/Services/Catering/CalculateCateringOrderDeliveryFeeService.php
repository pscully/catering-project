<?php

namespace App\Services\Catering;

class CalculateCateringOrderDeliveryFeeService
{
    public function run($orderTotal, $miles, $requiresSetup): float|int
    {
        $deliveryFee = 0;
        $setupFee = $requiresSetup ? 20 : 0; // Setup fee of $20 if required

        // Determine the base delivery fee and per mile rate after 10 miles based on order total
        if ($orderTotal < 300) {
            $deliveryFee = $miles <= 10 ? 30 : 30 + ($miles - 10) * 1.50;
        } elseif ($orderTotal >= 300 && $orderTotal < 700) {
            $deliveryFee = $miles <= 10 ? 40 : 40 + ($miles - 10) * 1.50;
        } elseif ($orderTotal >= 700) {
            $deliveryFee = $miles <= 10 ? 50 : 50 + ($miles - 10) * 1.50;
        }

        // Add setup fee for large orders requiring setup
        return $deliveryFee + $setupFee;
    }
}
