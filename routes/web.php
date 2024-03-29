<?php

use App\Http\Controllers\CateringOrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $orders = auth()->user()->cateringOrders()->get();
        return view('dashboard', ['orders' => $orders]);
    })->name('dashboard');

    Route::get('/order/{id}', [CateringOrderController::class, 'view'])->name('order.view');
});

Route::get('/order', function () {
    $user = auth()->user();
    return view('order', ['user' => $user]);
})->name('order');

Route::get('/catering-payment', function () {
    return view('catering-payment');
});

Route::post('/process-payment', [PaymentController::class, 'process'])->name('process-payment');
