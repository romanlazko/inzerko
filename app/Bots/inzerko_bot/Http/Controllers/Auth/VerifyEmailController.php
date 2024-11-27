<?php

namespace App\Bots\inzerko_bot\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TelegramEmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class VerifyEmailController extends Controller
{
    public function veryfyEmail(TelegramEmailVerificationRequest $request): RedirectResponse
    {
        if (! $request->user()->hasVerifiedEmail() AND $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect('https://t.me/'.$request->user()->chat?->bot?->username);
    }
}
