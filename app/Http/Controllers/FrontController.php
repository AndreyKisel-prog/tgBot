<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Telegram\WebhookNewRequestService;

class FrontController extends ApiController
{
    /**
     * * получаем  апдейт из вебхука (то есть все обновления поступающие от телеграма)
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhookUpdates(Request $request): JsonResponse
    {
        (new WebhookNewRequestService($request))->handle();
        return response()->json();
    }
}
