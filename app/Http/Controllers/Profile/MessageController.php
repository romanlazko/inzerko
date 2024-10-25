<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Messanger\Thread;
use App\Notifications\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MessageController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        $threads = auth()->user()->threads()
            ?->with('announcement:id', 'announcement.media', 'announcement.features', 'announcement.features.attribute', 'users:name,id', 'users.media', 'lastMessageRelation.user:id')
            
            ->whereHas('announcement')
            ->withCount(['messages as uread_messages_count' => function ($query) {
                $query->where('read_at', null)->where('user_id', '!=', auth()->id());
            }])
            ->get();

        return view('profile.message.index', compact('threads'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show(Thread $thread)
    {
        $thread->load('announcement:id', 'announcement.media');
        
        $thread->messages()->where('user_id', '!=', auth()->id())->whereNull('read_at')->update(['read_at' => now()]);

        Cookie::queue('unreadMessagesCount', auth()->user()?->unreadMessagesCount, 2);

        $messages = $thread->messages->load('user:id,name', 'user.media');

        return view('profile.message.show', compact('thread', 'messages'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $announcement = Announcement::findOrFail($request->announcement_id);

        if ($announcement->user->id == auth()->id()) {
            return redirect()->back()->with([
                'ok' => false,
                'description' => 'You can\'t send message to yourself',
            ]);
        }

        $thread = auth()->user()->threads()->where('announcement_id', $announcement->id)->first();

        if (!$thread) {
            $thread = auth()->user()->threads()->create([
                'announcement_id' => $announcement->id,
            ]);

            $thread->users()->attach([$announcement->user->id]);
        }

        return $this->update($thread, $request);
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Thread $thread, Request $request)
    {
        $thread->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $thread->recipient->notify((new NewMessage($thread))->delay(now()->addMinutes(3)));

        return redirect()->back()->with([
            'ok' => true,
            'description' => 'Message sent',
        ]);
    }
}
