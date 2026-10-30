@extends('layouts.frontbase')

@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="public-livewire-page activity-show">
    <div class="container">
        <div class="guest-request">
            <p class="activity-show__eyebrow">{{ site_trans('booking.guest_manage_eyebrow') }}</p>
            <h1>{{ site_trans('booking.guest_cancel_confirm_title') }}</h1>

            @if(! $editable)
                <p>{{ site_trans('booking.guest_closed') }}</p>
            @else
                <p class="activity-show__lead">{{ site_trans('booking.guest_cancel_confirm_lead', ['id' => $booking->id]) }}</p>
                <p><strong>{{ site_trans('booking.guest_cancel_reason') }}:</strong> {{ $reason }}</p>
                @if($errors->any())
                    <div class="activity-show__errors" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('guest-request.cancel-store', ['token' => $booking->manage_token]) }}" method="POST" class="activity-show__fields">
                    @csrf
                    <label class="is-wide guest-request__confirm">
                        <input type="checkbox" name="confirm_cancel" value="1" required>
                        <span>{{ site_trans('booking.guest_cancel_checkbox') }}</span>
                    </label>
                    <div class="is-wide guest-request__actions">
                        <button type="submit" class="theme-btn btn-style fill"><span>{{ site_trans('booking.guest_cancel_yes') }}</span></button>
                        <a href="{{ route('guest-request.cancel', ['token' => $booking->manage_token]) }}" class="theme-btn btn-style border"><span>{{ site_trans('booking.guest_cancel_back') }}</span></a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
