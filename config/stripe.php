<?php

return [
    'secret-key' => env('STRIPE_SECRET'),
    'published-key' => env('STRIPE_PUBLIC'),
    'webhook-secret' => env('STRIPE_WEBHOOK_SECRET'),
];
