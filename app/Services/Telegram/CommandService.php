<?php

namespace App\Services\Telegram;

use App\Objects\Commands\ChangeRadiusSearchCommand;
use App\Objects\Commands\UpdateLocationCommand;
use Illuminate\Support\Facades\Log;
use App\Objects\Commands\StartCommand;
use App\Objects\Commands\HelpCommand;

class CommandService extends BaseTelegramService
{

    public $command_message;
    const START_COMMAND = '/start';
    const HELP_COMMAND = '/help';
    const UPDATE_LOCATION_COMMAND = '/updatelocation';
    const CHANGE_RADIUS_SEARCH = '/changeradiussearch';

    public const COMANDS = [
        self::START_COMMAND => StartCommand::class,
        self::HELP_COMMAND =>HelpCommand::class,
        self::UPDATE_LOCATION_COMMAND =>UpdateLocationCommand::class,
        self::CHANGE_RADIUS_SEARCH=>ChangeRadiusSearchCommand::class,
    ];

    public function __construct($data)
    {
        parent::__construct($data);
        $this->command_message = $data['messageText'];
    }

    public function handle()
    {
        Log::info('CommandService @handle');
        $command = self::COMANDS[$this->command_message];
        return (new $command($this->data))->handle();
    }
}
