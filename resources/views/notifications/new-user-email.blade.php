@component('mail::message')
# Introduction

#Dear,

Your registration has success.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
