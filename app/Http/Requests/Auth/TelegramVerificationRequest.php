<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Romanlazko\Telegram\Models\TelegramChat;

class TelegramVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
        //     return false;
        // }

        // if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
        //     return false;
        // }

        // if ($this->user()->telegram_token !== $this->route('token')) {
        //     return false;
        // }

        if (! hash_equals(sha1($this->user()->telegram_token), $this->route('telegram_token'))) {
            return false;
        }
        

        if (! TelegramChat::find((int) $this->route('telegram_chat_id'))) {
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
            'telegram_chat_id' => ['required', 'exists:telegram_chats,id', 'unique:users,telegram_chat_id'],
            'telegram_token' => 'required',
        ];
    }
}
