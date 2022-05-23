<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Services\Telegram\WebhookNewRequestService;

class FrontController extends ApiController
{
    /**
     * * получаем  апдейт из вебхука (то есть все обновления поступающие от телеграма)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhookUpdates(Request $request): \Illuminate\Http\JsonResponse
    {
        (new WebhookNewRequestService($request))->handle();
        return response()->json();
    }
}
