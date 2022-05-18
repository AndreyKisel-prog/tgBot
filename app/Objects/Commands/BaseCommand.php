<?php

namespace App\Objects\Commands;

use App\Objects\TelegramProcess\TelegramProcessTrait;

abstract class BaseCommand implements CommandInterface
{
    public $data;
    use TelegramProcessTrait;
    public function __construct($data)
    {
        $this->data = $data;
    }
}
