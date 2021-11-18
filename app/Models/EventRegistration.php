<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EventRegistration extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = ['id', 'email'];

    protected $casts = [
        'session' => 'array'
    ];

    /**
     * Route notifications for the mail channel.
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->email;

        // Return email address and name...
        return [$this->email => $this->name];
    }

}
