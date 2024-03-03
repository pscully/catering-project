<?php

namespace App\Http\Controllers;

use App\Models\CateringOrder;
use App\Models\CateringOrderProduct;
use App\Models\CateringProduct;

class CateringOrderController extends Controller
{
    public function view($id)
    {
        $order = CateringOrder::find($id);

        $orderProducts = CateringOrderProduct::with('details')->where('order_id', $order->id)->get();

        $totalToCharge = $this->calculateGrandTotal($order);

        $products = $orderProducts->map(function ($orderProduct) {
            return [
                'name' => $orderProduct->details->name,
                'price' => $orderProduct->details->price,
                'quantity' => $orderProduct->quantity,
            ];
        })->toArray();

        return view('catering-order', ['order' => $order, 'user' => auth()->user(), 'products' => $products, 'totalToCharge' => $totalToCharge]);
    }

    public function calculateGrandTotal(CateringOrder $order)
    {
        $taxRate = 0.0825;
        return $order->total + $order->delivery_fee + $order->total * $taxRate;
    }
}
