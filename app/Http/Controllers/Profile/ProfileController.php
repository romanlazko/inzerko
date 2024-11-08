<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Rules\AtLeastOneSelected;
use App\Rules\AtLeastOneVisible;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use Laravolt\Avatar\Facade as Avatar;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        
        $request->user()->save();

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
            $request->user()->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return Redirect::route('profile.edit')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }

    public function updateCommunication(Request $request): RedirectResponse
    {
        $request->validate([
            'communication' => ['required', 'array', new AtLeastOneSelected('visible')],
            'communication.*.phone' => ['required_if_accepted:communication.*.visible'],
            'lang' => ['required', 'array'],
            'lang.*' => ['string', 'in:en,ru,cz'],
        ]);

        $request->user()->update([
            'communication' => $request->communication,
            'lang' => $request->lang,
        ]);

        return Redirect::route('profile.edit')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }

    public function security(Request $request): View
    {
        return view('profile.security', [
            'user' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

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
        $announcements = auth()->user()->wishlist()->isPublished()->latest()
            ->paginate(30)->withQueryString();

        return view('profile.wishlist', compact('announcements'));
    }

    public function my_announcements(Request $request): View
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
        $request->user()->update([
            'notification_settings' => $request->notification_settings,
        ]);

        return Redirect::route('profile.notifications')->with([
            'ok' => true,
            'description' => __('profile.saved'),
        ]);
    }
}
