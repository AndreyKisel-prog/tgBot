<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\TelegramUpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Services\Telegram\WebhookNewRequestService;

class FrontController extends ApiController
{
    /**
     * * получаем  апдейт из вебхука (то есть все обновления поступающие от телеграма)
     * @param TelegramUpdateRequest $request
     * @return JsonResponse
     */
    public function handleWebhookUpdates(TelegramUpdateRequest $request): JsonResponse
    {
        (new WebhookNewRequestService($request))->handle();
        return response()->json();
    }
}
