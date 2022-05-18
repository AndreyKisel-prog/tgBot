<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Log;
use App\Objects\Commands\StartCommand;
use App\Objects\Commands\HelpCommand;

class CommandService extends BaseTelegramService
{

    public $command_message;
    const START_COMMAND = '/start';
    const HELP_COMMAND = '/help';
    //     const UPDATE_LOCATION_COMMAND = '/updateLocation';
    //     const UPDATE_SEARCH_WORD_COMMAND = '/updateSearchWord';

    public const COMANDS = [
        self::START_COMMAND => StartCommand::class,
        self::HELP_COMMAND =>HelpCommand::class,
//        self::UPDATE_LOCATION_COMMAND,
//        self::UPDATE_SEARCH_WORD_COMMAND,
    ];

    public function __construct($data)
    {
        parent::__construct($data);
        $this->command_message = $data['textBotCommand'];
    }

    public function handle()
    {
        Log::info('CommandService @handle');
        $command = self::COMANDS[$this->command_message];
        return (new $command($this->data))->handle();
    }
}
