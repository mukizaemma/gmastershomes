@php
    $showLinks = filled($booking->manage_token ?? null)
        && in_array($booking->reservation_type ?? '', ['room', 'tour_activity'], true);
@endphp
@if($showLinks)
<p style="margin: 24px 0 12px;">Use the links below to change this request, or to cancel it before the start date. Cancelling asks for a reason, then asks you to confirm.</p>
<p style="margin: 0 0 8px;">
    <a href="{{ route('guest-request.manage', ['token' => $booking->manage_token]) }}" style="display: inline-block; background: #111111; color: #ffffff; text-decoration: none; padding: 12px 18px; margin: 0 8px 8px 0;">Manage reservation</a>
    <a href="{{ route('guest-request.cancel', ['token' => $booking->manage_token]) }}" style="display: inline-block; background: #ffffff; color: #111111; text-decoration: none; padding: 11px 18px; border: 1px solid #111111; margin: 0 0 8px 0;">Cancel reservation</a>
</p>
@endif
