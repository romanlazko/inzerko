<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Romanlazko\Telegram\Models\TelegramChat;

class TelegramVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        if (! hash_equals(sha1($this->user()->tokenByName('verify-telegram-connection')->token), $this->token)) {
            return false;
        }

        if (! TelegramChat::find((int) $this->route('telegram_chat_id'))) {
            return false;
        }

        if (User::where('telegram_chat_id', (int) $this->telegram_chat_id)->first()) {
            return false;
        }

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
            // 'telegram_chat_id' => ['required', 'exists:telegram_chats,id', 'unique:users,telegram_chat_id'],
        ];
    }
}
