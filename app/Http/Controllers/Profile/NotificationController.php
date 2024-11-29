<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Rules\AtLeastOneSelected;
use App\Rules\AtLeastOneVisible;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use libphonenumber\PhoneNumberUtil;

use Laravolt\Avatar\Facade as Avatar;

class NotificationController extends Controller
{

    public function edit(Request $request): View
    {
        return view('profile.notification.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'notification_settings' => ['array'],
        ]);

        ProfileService::update(
            user: $request->user(),
            notification_settings: $request->notification_settings
        );

        return Redirect::route('profile.notification.edit')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }
}
