<?php

namespace App\Bots\pozorbottestbot\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Announcement;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AnnouncementController extends Controller
{
    public function create(Request $request)
    {
        $user = User::where([
            'email' => $request->email,
            'telegram_chat_id' => $request->telegram_chat_id
        ])->first();

        if (!$user) {
            abort(403, 'Invalid credentials. User not found.');
        }

        Auth::login($user);

        return view('inzerko_bot::announcement.create');
    }
}
