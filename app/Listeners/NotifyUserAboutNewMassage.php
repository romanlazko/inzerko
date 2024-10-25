<?php

namespace App\Listeners;

use App\Events\NewMessage;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserAboutNewMassage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewMessage $event): void
    {
        $user = User::find($event->user_id)->notify();
    }
}
