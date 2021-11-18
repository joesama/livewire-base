@component('mail::message')
# Introduction

#Dear {{$recipient->name}},

Your registration has success.

Thank you for registering with our event.
The event can be access by url below.

The session you have registered for:
@component('mail::table')
    | Sessions | {{ implode(',', $recipient->session)  }} |
    | ---------|-------------------------------------------|
@endcomponent


@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
