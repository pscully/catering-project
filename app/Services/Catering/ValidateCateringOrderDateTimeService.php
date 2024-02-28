<?php

namespace App\Services\Catering;

use App\Rules\CateringOrderValidDateTime;

class ValidateCateringOrderDateTimeService
{
    public function run($date): bool
    {
        CateringOrderValidDateTime::validate($date);
    }
}
