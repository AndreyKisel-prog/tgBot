<?php

namespace App\Services\Tomtom;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

use function env;

class TomtomApiClient
{
    /** @var Http */
    protected $http;
    /** @var string */
    protected $tomtomAPIKey;

    public function __construct(Http $http, string $tomtomAPIKey)
    {
        $this->http         = $http;
        $this->tomtomAPIKey = $tomtomAPIKey;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $query
     * @param int $radius
     * @return Response
     */
    public function fetchPOI(
        float $latitude,
        float $longitude,
        string $query,
        int $radius
    ): Response {
        return $this->http::get(
            env('BASE_TOMTOM_URL') . $query . '.json',
            [
                'key'         => $this->tomtomAPIKey,
                'lat'         => $latitude,
                'lon'         => $longitude,
                'language'    => 'ru-Cyrl-RU',
                'relatedPois' => 'off',
                'view'        => 'Unified',
                'radius'      => $radius,
//              график работы
//              'openingHours'=
            ]
        );
    }
}
