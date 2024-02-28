<?php

namespace App\Services\Catering;

use App\Models\CateringOrder;
use App\Models\CateringOrderProduct;
use App\Models\CateringProduct;

class AttachCateringOrderProductsService
{
    public function run(CateringOrder $order, array $orderProducts): void
    {
        $products = CateringProduct::whereIn('sku', array_keys($orderProducts))->get()->keyBy('sku');

        foreach ($orderProducts as $sku => $quantity) {
            if (!$quantity == 0) {
                $product = $products->get($sku);

                if ($product === null) {
                    continue;
                }

                CateringOrderProduct::create([
                    'quantity' => $quantity,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                ]);
            }
        }
    }
}
