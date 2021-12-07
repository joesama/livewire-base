<?php

namespace App\Listeners;

use App\Events\UserEventRegistration;
use App\Mail\Events\NewEventUserEmail;
use App\Mail\Events\NewEventUserEmailMarkdown;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotifyRegisteredEventUser;


class SendEventRegistrationNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserEventRegistration  $event
     * @return void
     */
    public function handle(UserEventRegistration $event)
    {
        Mail::to($event->user->email)
            ->send(new NewEventUserEmail($event->user));

        Mail::to($event->user->email)
            ->send(new NewEventUserEmailMarkdown($event->user));

        $event->user->notify(new NotifyRegisteredEventUser($event->user));
    }
}
