<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Tomtom
{
    protected $http;
    protected $tomtomAPIKey;

    public function __construct(Http $http, $tomtomAPIKey)
    {
        $this->http = $http;
        $this->tomtomAPIKey = $tomtomAPIKey;
    }

    public function fetchPOI($latitude, $longitude, $query, $radius = null)
    {
        return $this->http::get(env('BASE_TOMTOM_URL') . $query . '.json',
            [
                'key' => $this->tomtomAPIKey,
                'lat' => $latitude,
                'lon' => $longitude,
                'language' => 'ru-Cyrl-RU',
                'relatedPois' => 'off',
                'view' => 'Unified',
                'radius' => $radius,
//    &openingHours=
//    &timezone=
            ]);
    }
}
