@component('mail::message')

# Welcome!

Hello {{ $user->name }},

Your account has been created successfully in the project management system.

### Login Details

**Email:** {{ $user->email }}

**Password:** {{ $plainPassword }}

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

For security, please reset your password after your first successful login.

Thanks,<br>
{{ config('app.name') }}

@endcomponent