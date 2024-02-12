@php
    $grandTotal = 0;
@endphp

<div class="flex">
    <div class="max-w-3xl w-full p-10 border border-black dark:border-white m-10 dark:text-black">
        <form wire:submit="placeCateringOrderSubmit">
            {{ $this->form }}

            <button type="submit" class="rounded bg-black px-6 py-4 text-white mt-4">
                Submit
            </button>
        </form>

        <x-filament-actions::modals/>
    </div>
    <div class="max-w-3xl w-full p-10 border border-black dark:border-white m-10">
        <div class="mt-8">
            <h2 class="text-2xl">Total for all products:</h2>
            ${{ number_format($grandTotal, 2) }}
            @foreach($orderProducts as $sku => $quantity)
                @php
                    $product = \App\Models\CateringProduct::where('sku', $sku)->first();
                    $productName = optional($product)->name; // Use optional() helper to avoid null property error
                    $productPrice = optional($product)->price;
                    $quantity = (int) $quantity;
                    $productTotalPrice = $productPrice * $quantity;
                    $grandTotal += $productTotalPrice;
                @endphp

                @if($product && $quantity > 0)
                    <div class="flex justify-between items-center mt-4">
                        <div>
                            <h3 class="text-xl">{{ $productName }}</h3>
                            <p>Quantity: {{ $quantity }}</p>
                            <p>Price: ${{ number_format($productTotalPrice, 2) }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
