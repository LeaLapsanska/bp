@component('mail::message')
# Zmena v udalosti

Používateľ: {{ $user->name }} sa odhlásil z vami vytvorenej udalosti: {{ $event->name }}.

@component('mail::button', ['url' => 'https://lapsanska.dgtl.sk/#/'])
Navštíviť web RMS
@endcomponent

Želáme Vám úspešný deň!<br>
{{ config('app.name') }}
@endcomponent
