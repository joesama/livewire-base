<?php

namespace App\Mail\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEventUserEmailMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->recipient = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('notifications.new-user-email', ['recipient' => $this->recipient])
            ->with([
                'url' => route('registration.new', ['newId' => $this->recipient->id])
            ])
            ->attachFromStorage('public/' . $this->recipient->avatar);
    }
}
