<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    public function process(Request $request)
    {

        $user = Auth::user();
        $user->addPaymentMethod($request->paymentMethodId);

        try {
            $payment = $user->payWith($request->amount, ['payment_method' => $request->paymentMethodId]);

            dd($payment);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing payment', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAmountInCents($amount)
    {
        return intval(
            round(
                floatval($amount) * 100,
                0
            )
        );
    }
}
