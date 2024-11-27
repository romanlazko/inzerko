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
        // $user = User::where([
        //     'telegram_token' => $request->get('telegram_token')
        // ])->first();

        $user = User::findByToken($request->get('token'));

        if (! $user) {
            abort(403, 'Invalid credentials. User not found.');
        }

        Auth::login($user);
        
        return redirect()->route('inzerko_bot.announcement.create');
    }
    
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
