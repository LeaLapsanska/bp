@component('mail::message')
# Nové heslo

Na základe Vašej žiadosti Vám bolo vygenerované heslo:  <b>{{ $random_password }}</b>.<br>
V prípade, že si chcete svoje heslo zmeniť, môžete tak urobiť v svojom profile.

@component('mail::button', ['url' => 'https://lapsanska.dgtl.sk/#/'])
    Prihlásiť sa
@endcomponent

Želáme Vám úspešný deň!<br>
{{ config('app.name') }}
@endcomponent
