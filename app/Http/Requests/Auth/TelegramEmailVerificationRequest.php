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
        if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->hash)) {
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
            'token' => 'required',
            'hash' => 'required',
        ];
    }

    public function user($guard = null): User
    {
        return User::findByTokenOrFail($this->token);
    }
}
