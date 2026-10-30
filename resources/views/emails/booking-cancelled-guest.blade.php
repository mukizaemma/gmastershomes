<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request cancelled</title>
</head>
<body style="font-family: Georgia, serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px;">Your request was cancelled</h1>
    <p>We cancelled request #{{ $booking->id }} at <strong>{{ $hotelName }}</strong>.</p>
    @if(filled($booking->cancellation_reason))
    <p><strong>Reason:</strong> {{ $booking->cancellation_reason }}</p>
    @endif
    <p>If this was a mistake, reply to this email and we will help you send a new request.</p>
</body>
</html>
