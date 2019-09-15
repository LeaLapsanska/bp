@component('mail::message')
# Zmena udalosti

Drahý používateľ,<br>
udalosť {{ $event->name }} bola zmenená!<br>
Začiatok | {{ $event->start }}<br>
Koniec | {{ $event->end }}<br>
Miestnosť | {{ $event->room->name }}

@component('mail::button', ['url' => 'https://lapsanska.dgtl.sk/#/'])
    Navštíviť web RMS
@endcomponent

Želáme Vám úspešný deň!<br>
{{ config('app.name') }}
@endcomponent
