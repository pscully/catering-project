<?php

namespace App\Services\Catering;

use App\Models\CateringProduct;

class CalculateCateringOrderTotalService
{
    public function run(array $orderProducts): float|int
    {
        $grandTotal = 0;

        foreach ($orderProducts as $sku => $quantity) {
            if ($quantity > 0) {
                $product = CateringProduct::where('sku', $sku)->first();
                $productPrice = optional($product)->price;
                $quantity = (int)$quantity;
                $productTotalPrice = $productPrice * $quantity;
                $grandTotal += $productTotalPrice;
            }
        }

        return $grandTotal;
    }
}
