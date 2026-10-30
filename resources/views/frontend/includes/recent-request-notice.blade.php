@php
    $recent = session('recent_request');
    $formId = $formId ?? '';
@endphp
@if(is_array($recent) && filled($recent['id'] ?? null))
    <div class="recent-request" role="status">
        <h3>{{ site_trans('booking.recent_title') }}</h3>
        <p>{{ site_trans('booking.recent_body', [
            'kind' => ($recent['kind'] ?? '') === 'tour' ? site_trans('booking.recent_kind_tour') : site_trans('booking.recent_kind_room'),
            'date' => $recent['created_on'] ?? '',
        ]) }}</p>
        @if(session('recent_request_link_sent'))
            <p class="recent-request__ok">{{ site_trans('booking.recent_link_sent') }}</p>
        @endif
        @if(session('recent_request_link_failed'))
            <p class="recent-request__bad">{{ site_trans('booking.recent_link_failed') }}</p>
        @endif
        <div class="recent-request__actions">
            @if($formId !== '')
                <button type="submit" class="theme-btn btn-style fill" form="{{ $formId }}" name="confirm_new_request" value="1">
                    <span>{{ site_trans('booking.recent_submit_new') }}</span>
                </button>
            @endif
            <form action="{{ route('guest-request.access-link') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $recent['email'] ?? '' }}">
                <input type="hidden" name="booking_id" value="{{ $recent['id'] }}">
                <button type="submit" class="theme-btn btn-style border">
                    <span>{{ site_trans('booking.recent_email_link') }}</span>
                </button>
            </form>
        </div>
    </div>
@endif
