Hi,
A new user has requested an account. Please review his/her request and
either <b>active</b> it or <b>deactive</b> it using the admin section.

<br>
<p>Click here to review the user's request:
    <a href="{{ route('users') }}" target="_blank">Review User</a>
</p>
<br>

A summary of his/her details is provided here.
<br>
- <b>Name and Surname:</b> {{ $user->name }} {{ $user->surname }}<br>
- <b>Date of birth:</b> {{ $user->dob }}<br>
- <b>Email:</b> {{ $user->email }}<br>
- <b>Role:</b> {{ $user->role }}<br>
- <b>Company:</b> {{ $user->company_role }}<br>

<br><br>
Please, do not respond to this email, email generate automatically.<br>
Best regards,<br>
DAMOCLES
