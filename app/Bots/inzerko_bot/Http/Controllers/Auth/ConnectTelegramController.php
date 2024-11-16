<?php

namespace App\Bots\inzerko_bot\Http\Controllers\Auth;

use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TelegramVerificationRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
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

        $bot_username = Inzerko::getBotChat()->username;

        // Log::info("Redirecting to: https://t.me/{$bot_username}?start=connect-{$telegram_token}");

        // return response()->json([
        //     'redirect_url' => "https://t.me/{$bot_username}?start=connect-{$telegram_token}"
        // ]);

        // return header("Location: https://t.me/{$bot_username}?start=connect-{$telegram_token}");

        return redirect('https://t.me/'.$bot_username.'?start=connect-'.$telegram_token);
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

        if (!$request->user()->hasVerifiedEmail() AND $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME)->with([
            'ok' => true,
            'description' => __('auth.telegram.verified'),
        ]);
    }
}
