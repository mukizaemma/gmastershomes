<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage your request</title>
</head>
<body style="font-family: Georgia, serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px;">Your recent request</h1>
    <p>You already sent a request to <strong>{{ $hotelName }}</strong> on {{ optional($booking->created_at)->format('j M Y') }}. Use the link below to update it, or to cancel it before the start date.</p>

    @include('emails.partials.booking-details', ['booking' => $booking])
    @include('emails.partials.guest-request-actions', ['booking' => $booking])
</body>
</html>
