<!DOCTYPE html>
<html>

<head>
    <title>{{ $subject }}</title>
</head>

<body>
    {!! nl2br(e($body)) !!}

    <!-- Tracking Pixel -->
    <img src="{{ route('phishing.campaign.opened', ['id' => $userPhishingEmailId]) }}" style="display:none;" width="1" height="1" alt="">
</body>

</html>
