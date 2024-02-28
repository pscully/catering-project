<?php

namespace App\Services\Catering;

class CalculateDeliveryDistanceMilesService
{
    public function run($selectedLocation, $customerLatitude, $customerLongitude): int
    {
        $theta = $selectedLocation->longitude - $customerLongitude;
        $dist = sin(deg2rad($selectedLocation->latitude)) * sin(deg2rad($customerLatitude)) +  cos(deg2rad($selectedLocation->latitude)) * cos(deg2rad($customerLatitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = round($dist * 60 * 1.1515);

        return $miles;
    }
}
