<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function locale(Request $request)
    {
        $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', array_keys(config('translate.languages')))],
        ]);

        if ($user = auth()->user()) {
            $user->update([
                'locale' => $request->locale
            ]);
        }

        session(['locale' => $request->locale]);

        return redirect()->back();
    }
}
