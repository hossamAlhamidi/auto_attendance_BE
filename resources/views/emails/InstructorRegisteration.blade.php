{{-- way 1 --}}
@component('mail::message')
# Welcome {{ $email['name'] }}

{{ $email['body'] }}

We advice you to change the password after you login.

@endcomponent

{{-- way 2 --}}
{{-- <x-mail::message>

Hello <strong> {{ $name }} </strong>

<p> {{ $body }} </p>

</x-mail::message> --}}
