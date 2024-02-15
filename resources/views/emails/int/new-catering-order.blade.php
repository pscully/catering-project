<x-mail::message>
# New Catering Order

A new catering order has been placed. Please check your dashboard to approve or decline this order.

<x-mail::panel>

Order ID: #{{ $orderId }}

Customer Email: {{ $customerEmail }}

Customer Phone: {{ $customerPhone }}

Order Total: ${{ number_format($orderTotal, 2) }}

Order Placed On: {{ $orderCreatedAt }}

Delivery or Pickup: {{ $delivery ? "Delivery" : "Pick Up" }}

Date For Pickup/Delivery: {{ date('m/d/Y', strtotime($orderDate)) }}

Order Time: {{ $orderTime }}

</x-mail::panel>

<x-mail::button url="http://catering.test/admin" color="primary">
View Order
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
