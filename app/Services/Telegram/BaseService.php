<?php

namespace App\Services\Telegram;

abstract class BaseService
{
    /** @var array */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    abstract public function handle();
}
