<x-mail::message>
# Bonjour,

Tu as été invité à rejoindre la colocation **{{ $invitation->colocation->name }}** sur EasyColoc.

Rejoins tes colocataires pour gérer vos dépenses communes en toute simplicité !

<x-mail::button :url="route('invitations.accept', $invitation->token)">
Accepter l'invitation
</x-mail::button>

Si tu n'as pas de compte, tu pourras en créer un après avoir cliqué sur le bouton.

Merci,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
