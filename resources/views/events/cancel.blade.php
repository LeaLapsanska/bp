@component('mail::message')
# Zrušenie udalosti

Drahý používateľ,<br>
udalosť {{ $event->name }}, so začiatkom {{ $event->start }} a koncom {{ $event->end }} v {{ $event->room->name }} bola zrušená!

@component('mail::button', ['url' => 'https://lapsanska.dgtl.sk/#/'])
    Navštíviť web RMS
@endcomponent

Želáme Vám úspešný deň!<br>
{{ config('app.name') }}
@endcomponent
