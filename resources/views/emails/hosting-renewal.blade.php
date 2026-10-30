<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $stage === 'expired' ? 'Hosting has expired' : 'Hosting renewal' }}</title>
</head>
<body style="font-family: Georgia, serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <p style="margin: 0 0 8px; letter-spacing: 0.04em; text-transform: uppercase; font-size: 12px; color: #666;">{{ config('hosting.issuer') }}</p>
    <h1 style="font-size: 22px; margin: 0 0 16px;">
        @if($stage === 'manual')
            Payment reminder
        @elseif($stage === 'expired')
            Hosting has expired
        @elseif($stage === 'today')
            Hosting expires today
        @else
            Hosting expires in {{ $days }} {{ $days === 1 ? 'day' : 'days' }}
        @endif
    </h1>

    <p>
        The hosting year for {{ config('hosting.client') }} runs until
        <strong>{{ $coverage->period_end?->format('j F Y') }}</strong>.
        @if($stage === 'manual')
            This is a reminder to settle invoice {{ $due?->number ?: $coverage->number }}.
        @elseif($stage === 'expired')
            That date has passed. The renewal invoice is ready in the admin.
        @elseif($stage === 'today')
            Arrange payment today so the site stays online.
        @else
            Please arrange payment before that date.
        @endif
    </p>

    <p>
        Amount due: <strong>{{ $amount }}</strong>
        @if($due)
            · Invoice {{ $due->number }}
        @else
            · The invoice is created on 1 October and then stays in the admin for download.
        @endif
    </p>

    <p style="margin: 0 0 8px;"><strong>Payment methods</strong></p>
    @foreach($paymentMethods as $method)
        <p style="margin: 0 0 12px;">
            <strong>{{ $method['name'] }}</strong><br>
            @foreach($method['lines'] as $line)
                {{ $line }}@if(! $loop->last)<br>@endif
            @endforeach
        </p>
    @endforeach

    <p style="margin: 24px 0;">
        <a href="{{ route('content-management.hosting.index') }}" style="display: inline-block; background: #111111; color: #fff; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-weight: bold;">Open the hosting invoice</a>
    </p>
    <p style="font-size: 13px; color: #666;">After the transfer, a super admin marks the invoice paid. This reminder is sent once for this stage.</p>
</body>
</html>
