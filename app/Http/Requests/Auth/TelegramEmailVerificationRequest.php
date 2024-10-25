<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Romanlazko\Telegram\Models\TelegramChat;

class TelegramEmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        if (is_null($user = User::firstWhere(['email' => $this->email, 'telegram_chat_id' => $this->telegram_chat_id, 'telegram_token' => $this->telegram_token]))) {
            return false;
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $this->hash)) {
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
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'telegram_chat_id' => ['required', 'string', 'exists:telegram_chats,id'],
            'telegram_token' => ['required', 'string', 'exists:users,telegram_token'],
            'token' => 'required',
            'hash' => 'required',
        ];
    }
}
