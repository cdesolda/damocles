Hi {{ $user->name }},<br>

@if ($user->is_active)
    Your account is already active: you can use DAMOCLES right away.
@else
    We've received your registration request. One of our admins will examine it and
    you'll receive another email once your account request will be either accepted
    or declined.
@endif

<br><br>
Please, do not respond to this email, email generate automatically.<br>
Best regards,<br>
DAMOCLES
