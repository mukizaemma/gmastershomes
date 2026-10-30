@extends('layouts.frontbase')

@section('meta_robots', 'noindex, nofollow')

@section('content')
@php
    $isTour = ($booking->reservation_type ?? '') === 'tour_activity';
    $selectedActivityIds = array_map('intval', (array) old('activity_ids', $selectedActivityIds ?? []));
    if ($selectedActivityIds === [] && $booking->tour_activity_id) {
        $selectedActivityIds = [(int) $booking->tour_activity_id];
    }
@endphp
<div class="public-livewire-page activity-show">
    <div class="container">
        <div class="guest-request">
            <p class="activity-show__eyebrow">{{ site_trans('booking.guest_manage_eyebrow') }}</p>
            <h1>{{ site_trans('booking.guest_manage_title') }}</h1>
            <p class="activity-show__lead">{{ site_trans('booking.guest_reference', ['id' => $booking->id]) }}</p>

            @if(session('success'))
                <div class="recent-request__ok" role="status">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="activity-show__errors" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(! $editable)
                <p>{{ $booking->status === 'cancelled' ? site_trans('booking.guest_already_cancelled') : site_trans('booking.guest_closed') }}</p>
                @if(filled($booking->cancellation_reason))
                    <p><strong>{{ site_trans('booking.guest_cancel_reason') }}:</strong> {{ $booking->cancellation_reason }}</p>
                @endif
            @else
                <form action="{{ route('guest-request.update', ['token' => $booking->manage_token]) }}" method="POST" class="activity-show__fields">
                    @csrf
                    <label>
                        <span>{{ site_trans('booking.form_name') }} *</span>
                        <input type="text" name="names" value="{{ old('names', $booking->names) }}" required>
                    </label>
                    <label>
                        <span>{{ site_trans('booking.form_phone') }} *</span>
                        <input type="tel" name="phone" value="{{ old('phone', $booking->phone) }}" required>
                    </label>
                    <label class="is-wide">
                        <span>{{ site_trans('booking.form_email') }} *</span>
                        <input type="email" name="email" value="{{ old('email', $booking->email) }}" required>
                    </label>

                    @if($isTour)
                        @include('frontend.includes.tour-request-fields', [
                            'activities' => $activities,
                            'selectedActivityIds' => $selectedActivityIds,
                            'accommodation' => old('accommodation_preference', $booking->accommodation_preference),
                            'transport' => old('transport_preference', $booking->transport_preference),
                            'destinations' => old('destinations', $booking->destinations),
                            'start' => old('activity_start', optional($booking->checkin_date)->toDateString()),
                            'end' => old('activity_end', optional($booking->checkout_date)->toDateString()),
                            'guests' => old('guests', $booking->adults),
                            'notes' => old('notes', $booking->message),
                        ])
                    @else
                        <label class="is-wide">
                            <span>{{ site_trans('booking.form_room') }} *</span>
                            <select name="room_id" required>
                                @foreach($rooms as $roomOption)
                                    <option value="{{ $roomOption->id }}" @selected((int) old('room_id', $booking->room_id) === (int) $roomOption->id)>{{ $roomOption->title }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_checkin') }} *</span>
                            <input type="date" name="checkin" value="{{ old('checkin', optional($booking->checkin_date)->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_checkout') }} *</span>
                            <input type="date" name="checkout" value="{{ old('checkout', optional($booking->checkout_date)->toDateString()) }}" required>
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_adults') }} *</span>
                            <input type="number" name="adults" min="1" value="{{ old('adults', $booking->adults) }}" required>
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_children') }}</span>
                            <input type="number" name="children" min="0" value="{{ old('children', $booking->children) }}">
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_rooms') }}</span>
                            <input type="number" name="rooms" min="1" value="{{ old('rooms', $booking->rooms ?: 1) }}">
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_country') }}</span>
                            <input type="text" name="country" value="{{ old('country', $booking->country) }}">
                        </label>
                        <label class="is-wide">
                            <span>{{ site_trans('booking.form_stay_reason') }}</span>
                            <select name="stay_reason">
                                <option value="">{{ site_trans('booking.form_stay_reason_placeholder') }}</option>
                                @foreach($stayReasons as $key => $label)
                                    <option value="{{ $key }}" @selected(old('stay_reason', $booking->stay_reason) === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="is-wide">
                            <span>{{ site_trans('booking.channel_requests') }}</span>
                            <textarea name="message" rows="4">{{ old('message', $booking->message) }}</textarea>
                        </label>
                    @endif

                    <div class="is-wide guest-request__actions">
                        <button type="submit" class="theme-btn btn-style fill"><span>{{ site_trans('booking.guest_save') }}</span></button>
                        <a href="{{ route('guest-request.cancel', ['token' => $booking->manage_token]) }}" class="theme-btn btn-style border"><span>{{ site_trans('booking.guest_email_cancel') }}</span></a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
