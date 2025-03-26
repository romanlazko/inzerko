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
            ->isPublished()
            ->isActive()
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
}
