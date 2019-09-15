@component('mail::message')
# Pozvánka

Drahý používateľ,<br>
bol si pozvaný na udalosť:<br>

<b>{{ $event->name }}</b><br>
Začiatok udalosti | {{ $event->start }} <br>
Koniec udalosti | {{ $event->end }}<br>
Miestnosť | {{ $event->room->name }}<br>

Zapisovať do kalendára si nemusíš! Kalendár všetkých svojich udalostí nájdeš u nás!
@component('mail::button', ['url' => 'https://lapsanska.dgtl.sk/#/'])
Navštíviť web RMS
@endcomponent

Želáme Vám úspešný deň!<br>
{{ config('app.name') }}
@endcomponent
