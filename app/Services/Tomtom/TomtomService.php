<?php

namespace App\Services\Tomtom;

use App\Services\BaseService;
use App\Transformers\TomtomTransformer;
use Illuminate\Support\Facades\Http;

class TomtomService extends BaseService
{
    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $message
     * @param int $radius
     * @return array
     */
    public function handle(
        float $latitude,
        float $longitude,
        string $message,
        int $radius
    ): array {
        $tomtom   = new TomtomApiClient(new Http(), env('TOMTOM_API_KEY'));
        $responce = $tomtom->fetchPOI(
            $latitude,
            $longitude,
            $message,
            $radius
        );
        return (new TomtomTransformer($responce))->handle();
    }
}


