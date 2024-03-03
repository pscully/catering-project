<form wire:submit="submitAndCreateOrder">
    <label for="card-holder-name">Card Holder Name</label>
    <input id="card-holder-name" type="text" placeholder="Card Holder Name">
    <div id="card-element"></div>

    <button type="submit" id="card-button" class="rounded bg-black px-6 py-4 text-white mt-4">
        Submit My Order
    </button>
</form>

<script>
    const stripe = Stripe("stripe-public-key");
    const elements = stripe.elements();
    const cardElement = elements.create("card");
    cardElement.mount("#card-element");
    const cardHolderName = document.getElementById("card-holder-name");
    const cardButton = document.getElementById("card-button");
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
                body: JSON.stringify({paymentMethodId: paymentMethod.id, amount: {{$totalToCharge}}})
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
