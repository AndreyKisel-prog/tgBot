<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Services\Telegram\WebhookNewRequestService;
use Illuminate\Support\Facades\Log;

class FrontController extends ApiController
{
    /**
     * получаем обеект апдейта из вебхука (то есть все обновления поступающие от телеграма)
     *
     *
     */
    public function handleWebhookUpdates(Request $request)
    {
        (new WebhookNewRequestService($request))->handle();
        return response()->json(['result' => true], 200);
    }
}
