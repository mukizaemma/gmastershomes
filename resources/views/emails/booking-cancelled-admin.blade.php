<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request cancelled</title>
</head>
<body style="font-family: Georgia, serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px;">A guest cancelled a request</h1>
    <p><strong>{{ $booking->names }}</strong> cancelled request #{{ $booking->id }}.</p>
    @if(filled($booking->cancellation_reason))
    <p><strong>Reason:</strong> {{ $booking->cancellation_reason }}</p>
    @endif

    @include('emails.partials.booking-details', ['booking' => $booking])

    <p style="margin: 24px 0;">
        <a href="{{ route('content-management.reservations.show', $booking->id) }}" style="display: inline-block; background: #111111; color: #fff; text-decoration: none; padding: 12px 20px;">Open this reservation</a>
    </p>
</body>
</html>
