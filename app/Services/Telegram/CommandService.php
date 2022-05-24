<?php

namespace App\Services\Telegram;

use App\Assets\MessageTextToSend;
use App\Models\User;
use App\Services\Telegram\SearchConditions;
use Illuminate\Http\Client\Response;

class CommandService extends BaseService
{
    use ProcessTrait;

    /** @var string */
    public $command_message;


    public const START = '/start';
    public const HELP = '/help';
    public const UPDATE_LOCATION = '/update_location';
    public const CHANGE_RADIUS_SEARCH = '/change_radius_search';

    // запускаемые для конкретных команд методы
    public const COMMANDS = [
        self::START                => 'startCommandHandle',
        self::HELP                 => 'helpCommandHandle',
        self::UPDATE_LOCATION      => 'updateLocationCommandHandle',
        self::CHANGE_RADIUS_SEARCH => 'changeRadiusCommandHandle'
    ];

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->command_message = $data['messageText'];
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $command = self::COMMANDS[$this->command_message];
        return $this->$command();
    }

    public function startCommandHandle()
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['greeting']
            . $this->data['userName']
            . MessageTextToSend::MESSAGE_TEXT_TYPES['intro'];

        // отправляем приветственное сообщение
        $this->telegram()
            ->sendMessage(
                $this->data['chatId'],
                $message,
            );
        $user = User::getUser($this->data['nickName']);
        return (new SearchConditions($this->data))->checkConditionsForSearch($user);
    }

    /**
     * @return Response
     */
    private function helpCommandHandle(): Response
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['helpAnswer'];
        // отправляем сообщение с реакцией на просьбу о помощи
        return $this->telegram()
            ->sendMessage($this->data['chatId'], $message);
    }

    /**
     * обработка команды на обновление локации
     * @return Response
     */
    private function updateLocationCommandHandle(): Response
    {
        return (new SearchConditions($this->data))->locationNeedQuery();
    }

    /**
     * обработка команды на обновление радиуса
     * @return Response
     */
    public function changeRadiusCommandHandle(): Response
    {
        return (new SearchConditions($this->data))->radiusNeedQuery();
    }
}
