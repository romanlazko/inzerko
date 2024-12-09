<?php

namespace App\Http\Controllers\Profile;

use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Rules\AtLeastOneSelected;
use App\Rules\AtLeastOneVisible;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use libphonenumber\PhoneNumberUtil;

use Laravolt\Avatar\Facade as Avatar;

class AnnouncementController extends Controller
{

    public function index(): Response
    {
        return response()
            ->view('profile.announcement.index')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }

    public function create(): Response
    {
        $chat = auth()->user()->chat;

        try {
            Inzerko::editMessageReplyMarkup([
                'chat_id'       => $chat->chat_id,
                'message_id'    => $chat->latestMessage->message_id,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()
            ->view('profile.announcement.create')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }

    public function wishlist(Request $request): Response
    {
        $announcements = $request->user()
            ->wishlist()
            ->with([
                'media',
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
                'votes' =>  fn ($query) => $query->where('user_id', auth()->id()),
                'category.media'
            ])
            ->isPublished()
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return response()
            ->view('profile.announcement.wishlist', compact('announcements'))
            ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }
}
