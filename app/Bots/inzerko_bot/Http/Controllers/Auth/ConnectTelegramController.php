<?php

namespace App\Bots\inzerko_bot\Http\Controllers\Auth;

use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TelegramVerificationRequest;
use App\Models\AccessToken;
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
        $token = auth()->user()->createAccessToken('connect-telegram', null, 10)->token;

        $bot_username = Inzerko::getBotChat()->username;

        return redirect("https://t.me/{$bot_username}?start=connect-{$token}");
    }

    public function disconnectTelegram(): RedirectResponse
    {
        auth()->user()->update([
            'telegram_chat_id' => null,
            'telegram_token' => null,
        ]);

        return redirect()->intended(RouteServiceProvider::HOME)->with([
            'ok' => true,
            'description' => __('auth.telegram.disconnected'),
        ]);
    }

    public function verifyTelegramConnection(TelegramVerificationRequest $request): RedirectResponse
    {
        $request->user()->update([
            'telegram_chat_id' => $request->telegram_chat_id,
        ]);

        if (! $request->user()->hasVerifiedEmail() AND $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME)->with([
            'ok' => true,
            'description' => __('auth.telegram.verified'),
        ]);
    }
}
