<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request updated</title>
</head>
<body style="font-family: Georgia, serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px;">Your request was updated</h1>
    <p>We saved the changes to your request at <strong>{{ $hotelName }}</strong>. The details below are what we have now.</p>

    @include('emails.partials.booking-details', ['booking' => $booking])
    @include('emails.partials.guest-request-actions', ['booking' => $booking])
</body>
</html>
