@component('mail::message')
# Change password Request

Click below to reset the password.

@component('mail::button', ['url' => 'http://localhost:4200/response-password-reset?token='.$token])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }} 
@endcomponent
