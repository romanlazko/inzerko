<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TelegramVerificationRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ConnectTelegramController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function connectTelegram()
    {
        $telegram_token = Str::random(10);

        auth()->user()->update([
            'telegram_token' => $telegram_token,
        ]);

        return redirect("https://t.me/pozorbottestbot?start=connect-{$telegram_token}");
    }

    public function verifyTelegramConnection(TelegramVerificationRequest $request): RedirectResponse
    {
        $request->user()->update([
            'telegram_chat_id' => $request->telegram_chat_id,
        ]);

        return redirect()->intended(RouteServiceProvider::HOME)->with([
            'ok' => true,
            'description' => __('auth.telegram.verified'),
        ]);
    }
}
