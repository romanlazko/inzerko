<?php

namespace App\Bots\inzerko_bot\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AuthenticatedSessionController extends Controller
{
    public function auth(Request $request)
    {
        dd($request->signature);
        if (! $request->hasValidSignature(false)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where([
            'email' => $request->email,
            'telegram_chat_id' => $request->telegram_chat_id
        ])->first();

        if (!$user) {
            abort(403, 'Invalid credentials. User not found.');
        }

        Auth::login($user);
        
        return redirect()->route($request->to_route);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
