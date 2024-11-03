<?php

namespace App\Bots\inzerko_bot\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TelegramEmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class VerifyEmailController extends Controller
{
    public function veryfyEmail(TelegramEmailVerificationRequest $request): RedirectResponse
    {
        if (is_null($user = Password::getUser($request->only('email', 'telegram_token')))) {
            abort(403, 'Invalid credentials. User not found.');
        }

        if (! Password::tokenExists($user, $request->token)) {
            abort(403, 'Invalid token.');
        }

        if ($user->markEmailAsVerified()) {

            $user->update([
                'telegram_chat_id' => $request->telegram_chat_id,
            ]);
            
            Password::deleteToken($user);

            event(new Verified($user));
        }

        return redirect('https://t.me/'.$user->chat?->bot?->username);
    }
}
