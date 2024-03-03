<x-app-layout>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Checkout Order # {{$order->id}}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4">Please review your order and enter your payment details. Your card will not be charged until
                Sunflour Baking Company confirms your order.</p>

            <div class="mb-4">
                Date: <span class="font-semibold">{{$order->order_date->format('m/d/Y')}}</span> <br>
                Time: <span class="font-semibold">{{$order->order_time}}</span> <br>
                Delivery?: <span class="font-semibold">{{$order->delivery ? "Yes" : "No"}}</span> <br>
                Setup?: <span class="font-semibold">{{$order->setup? "Yes" : "No"}}</span> <br>
                Pickup Person: <span class="font-semibold">{{$order->pickup_first_name}}</span> <br>
                Order Total: <span class="font-semibold">${{$order->total}}</span> <br>
                Delivery Charge: <span class="font-semibold">${{$order->delivery_fee}}</span> <br>
                Grand Total: <span class="font-semibold">${{$order->total + $order->delivery_fee}}</span> <br>
            </div>

            <div class="mb-4 font-semibold">Items Ordered:</div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Quantity
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{$product['name']}}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{$product['price']}}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{$product['quantity']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <form class="mt-8 space-y-6">
                <input id="card-holder-name" type="text" placeholder="Card Holder Name"
                       class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                <div id="card-element"
                     class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"></div>

                <button type="submit" id="card-button"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Submit My Order
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
<script>
    const stripe = Stripe('pk_test_aq9gKmss4CXBk01UoYeowolY');
    const elements = stripe.elements();
    const cardElement = elements.create("card");
    cardElement.mount("#card-element");
    const cardHolderName = document.getElementById("card-holder-name");
    const cardButton = document.getElementById("card-button");
    const total = '{{($order->total + $order->delivery_fee) * 100}}';
    cardButton.addEventListener("click", async (e) => {
        e.preventDefault();
        const {paymentMethod, error} = await stripe.createPaymentMethod(
            "card", cardElement, {
                billing_details: {name: cardHolderName.value}
            }
        );
        if (error) {
            // Display "error.message" to the user...
        } else {
            fetch('/process-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({paymentMethodId: paymentMethod.id, amount: total})
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(data => {
                // Handle server response here
            }).catch(error => {
                // Handle error here
            });
        }
    });
</script>
