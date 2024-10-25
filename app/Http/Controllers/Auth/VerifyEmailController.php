<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TelegramEmailVerificationRequest;
use App\Notifications\VerificationSuccessNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class VerifyEmailController extends Controller
{
    public function notice(Request $request): RedirectResponse
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : redirect()->route('profile.edit');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function veryfyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function veryfyEmailTelegram(TelegramEmailVerificationRequest $request): RedirectResponse
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

        return redirect('tg://resolve?domain=pozorbottestbot');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with([
            'ok' => true,
            'description' => __('auth.verify_email_link_sent')
        ]);
    }
}
