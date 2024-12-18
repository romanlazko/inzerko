<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\AtLeastOneSelected;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

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
            'communication_settings.*.phone' => ['required_if_accepted:communication_settings.*.visible', 'nullable', 'phone:AUTO'],
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
}
