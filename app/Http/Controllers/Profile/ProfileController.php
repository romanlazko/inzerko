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

class ProfileController extends Controller
{

    public function show(User $user): View
    {
        $announcements = $user->announcements()
            ->with([
                'category', 
                'media',    
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
                'votes',
            ])
            ->orderBy('category_id')
            ->paginate(10);

        return view('profile.show', [
            'user' => $user,
            'announcements' => $announcements,
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]);

        ProfileService::update(
            user: $request->user(),
            name: $request->name,
            email: $request->email
        );

        return Redirect::route('profile.edit')->with([
            'ok' => true, 
            'description' => __('profile.saved')
        ]);
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            ProfileService::addMedia($request->user(), $request->file('avatar'));
        }

        return Redirect::route('profile.edit')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }

    public function updateCommunication(Request $request): RedirectResponse
    {
        $request->validate([
            'communication_settings' => ['required', 'array', new AtLeastOneSelected('visible')],
            'communication_settings.*.phone' => ['required_if_accepted:communication_settings.*.visible', 'nullable', 'phone:CZ'],
            'communication_settings.languages' => ['required', 'array'],
            'communication_settings.languages.*' => ['string', 'in:en,ru,cz'],
        ]);
        
        ProfileService::update(
            user: $request->user(),
            communication_settings: $request->communication_settings,
        );

        return Redirect::route('profile.edit')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }

    public function security(): View
    {
        return view('profile.security');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        ProfileService::update(
            user: $request->user(),
            password: $validated['password']
        );

        return back()->with('status', __('password-updated'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function wishlist()
    {
        $announcements = auth()->user()
            ->wishlist()
            ->isPublished()
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('profile.wishlist', [
            'announcements' => $announcements
        ]);
    }

    public function myAnnouncements(): View
    {
        return view('profile.my-announcements');
    }

    public function notifications(Request $request): View
    {
        return view('profile.notifications', [
            'user' => $request->user(),
        ]);
    }

    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->validate([
            'notification_settings' => ['array'],
        ]);

        ProfileService::update(
            user: $request->user(),
            notification_settings: $request->notification_settings
        );

        return Redirect::route('profile.notifications')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }
}
