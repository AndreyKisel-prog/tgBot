<?php

namespace App\Http\Requests;

use App\Exceptions\TelegramValidationException;
use App\Services\Telegram\ProcessTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TelegramUpdateRequest extends FormRequest
{
    use ProcessTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // авторизации не требуется, поэтому возвращаем true
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'update_id'               => 'nullable|int',
            // обьект сообщений
            'message.date'            => 'nullable|int',
            // данные о чате
            'message.chat.last_name'  => 'nullable|string',
            'message.chat.id'         => 'nullable|int',
            'message.chat.type'       => 'nullable|string', //private
            'message.chat.first_name' => 'nullable|string',
            'message.chat.username'   => 'nullable|string',
            'message.text'            => 'nullable|max:50',
            'message.message_id'      => 'nullable|int',
            // от кого
            'message.from.last_name'  => 'nullable|string',
            'message.from.id'         => 'nullable|int',
            'message.from.first_name' => 'nullable|string',
            'message.from.username'   => 'nullable|string',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'message.text.max' => 'The :attribute has too much symbols',
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     * @throws TelegramValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        //в случае нарушения правил валидации - выбрасывается ошибка
        throw new TelegramValidationException($validator->errors());
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'message.text' => 'search message',
        ];
    }
}
