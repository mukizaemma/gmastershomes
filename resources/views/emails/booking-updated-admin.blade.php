<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request updated</title>
</head>
<body style="font-family: Georgia, serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px;">A guest updated a request</h1>
    <p><strong>{{ $booking->names }}</strong> — {{ $booking->email }} — {{ $booking->phone }}</p>
    <p>Reference #{{ $booking->id }} was changed from the link in the guest email.</p>

    @include('emails.partials.booking-details', ['booking' => $booking])

    @if(filled($booking->message))
    <p style="margin: 0 0 8px;"><strong>Notes</strong></p>
    <p style="margin: 0 0 24px; white-space: pre-wrap;">{{ $booking->message }}</p>
    @endif

    <p style="margin: 24px 0;">
        <a href="{{ route('content-management.reservations.show', $booking->id) }}" style="display: inline-block; background: #111111; color: #fff; text-decoration: none; padding: 12px 20px;">Open this reservation</a>
    </p>
</body>
</html>
