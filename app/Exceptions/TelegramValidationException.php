<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class TelegramValidationException extends Exception
{
    /**
     * @return void
     */
    public function handle(){
        Log::error('TelegramValidationException');
    }
}
