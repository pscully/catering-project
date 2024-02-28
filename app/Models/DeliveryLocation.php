<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'apartment_suite',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'is_business',
        'business_name',
    ];

    protected $appends = [
        'location',
    ];

    public function getLocationAttribute(): array
    {
        return [
            "lat" => (float)$this->latitude,
            "lng" => (float)$this->longitude,
        ];
    }

    public function setLocationAttribute(?array $location): void
    {
        if (is_array($location)) {
            $this->attributes['latitude'] = $location['lat'];
            $this->attributes['longitude'] = $location['lng'];
            unset($this->attributes['location']);
        }
    }

    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

    public function getCoordinates($address)
    {
        $client = new Client();

        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'address' => $address,
                'key' => 'AIzaSyD5662MTg3itcKWihNrn-lI3qXY31m_bOY'
            ]
        ]);

        $response = json_decode($response->getBody(), true);

        if (!empty($response['results'])) {
            return $response['results'][0]['geometry']['location'];
        }

        return null;
    }

    public function setCoordinatesAttribute(?array $coordinates): void
    {
        $this->attributes['latitude'] = $coordinates['lat'];
        $this->attributes['longitude'] = $coordinates['lng'];
    }

    public function calculateDistance($customerLatitude, $customerLongitude): float
    {
        $theta = $this->location->longitude - $customerLongitude;
        $dist = sin(deg2rad($this->location->latitude)) * sin(deg2rad($customerLatitude)) +  cos(deg2rad($this->location->latitude)) * cos(deg2rad($customerLatitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles;
    }

    public static function getComputedLocation(): string
    {
        return 'location';
    }
}
