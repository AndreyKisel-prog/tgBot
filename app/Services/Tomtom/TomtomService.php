<?php

namespace App\Services\Tomtom;

use App\Helpers\Tomtom;
use App\Services\BaseService;
use Illuminate\Support\Facades\Http;
use App\Transformers\TomtomTransformer;
use Illuminate\Support\Facades\Log;

class TomtomService extends BaseService
{
    public function handle($latitude, $longitude, $message, $radius)
    {
        $tomtom = new Tomtom(new Http(), env('TOMTOM_API_KEY'));
        $responseTomtom = $tomtom->fetchPOI($latitude, $longitude, $message, $radius);
        return (new TomtomTransformer($responseTomtom))->handle();
    }
}


