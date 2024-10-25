<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if (User::firstWhere('email', $request->email)?->password == null) {
            $status = Password::sendResetLink(
                $request->only('email')
            );
    
            return $status == Password::RESET_LINK_SENT
                        ? back()->with([
                            'ok' => true,
                            'description' => __($status),
                            'status'      => __($status),
                        ])
                        : back()->withInput($request->only('email'))
                                ->withErrors(['email' => __($status)]);
        }

        return redirect()->route('auth', ['email' => $request->email]);
    }
}
