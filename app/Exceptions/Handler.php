<?php

namespace App\Exceptions;

use App\Services\Telegram\ProcessTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ProcessTrait;

    /**
     * @param $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TelegramValidationException) {
            $chatId  = (json_encode($request->toArray()["message"]["chat"]["id"]));
            $message = (explode('"', $exception->getMessage()))[3];
            $this->telegram()->sendMessage($chatId, $message);
        }
    }

}
