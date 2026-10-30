@extends('layouts.frontbase')

@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="public-livewire-page activity-show">
    <div class="container">
        <div class="guest-request">
            <p class="activity-show__eyebrow">{{ site_trans('booking.guest_manage_eyebrow') }}</p>
            <h1>{{ site_trans('booking.guest_cancel_title') }}</h1>

            @if(! $editable)
                <p>{{ $booking->status === 'cancelled' ? site_trans('booking.guest_already_cancelled') : site_trans('booking.guest_closed') }}</p>
                <a href="{{ route('guest-request.manage', ['token' => $booking->manage_token]) }}">{{ site_trans('booking.guest_back_to_request') }}</a>
            @else
                <p class="activity-show__lead">{{ site_trans('booking.guest_cancel_lead', ['id' => $booking->id]) }}</p>
                @if($errors->any())
                    <div class="activity-show__errors" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('guest-request.cancel-review', ['token' => $booking->manage_token]) }}" method="POST" class="activity-show__fields">
                    @csrf
                    <label class="is-wide">
                        <span>{{ site_trans('booking.guest_cancel_reason') }} *</span>
                        <textarea name="reason" rows="5" required minlength="5">{{ old('reason') }}</textarea>
                    </label>
                    <div class="is-wide guest-request__actions">
                        <button type="submit" class="theme-btn btn-style fill"><span>{{ site_trans('booking.guest_cancel_continue') }}</span></button>
                        <a href="{{ route('guest-request.manage', ['token' => $booking->manage_token]) }}" class="theme-btn btn-style border"><span>{{ site_trans('booking.guest_cancel_back') }}</span></a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
