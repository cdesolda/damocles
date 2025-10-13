Hi {{ $user->name }},<br>

One of our admins has <b>{{ $user->is_active ? 'activated' : 'deactivated' }}</b> your account. You can now
@unless ($user->is_active)
    no longer
@endunless login to DAMOCLES.

@unless ($user->is_active)
    <br>Note that you account has not been deleted: if an admin re-activates your
    account you'll be able to use DAMOCLES.<br>
@endunless

<br><br>
Please, do not respond to this email, email generate automatically.<br>
Best regards,<br>
DAMOCLES
