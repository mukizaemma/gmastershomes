@php
    $accommodationOptions = [
        'Mid-range lodges' => site_trans('booking.activity_accommodation_mid'),
        'Budget' => site_trans('booking.activity_accommodation_budget'),
        'Luxury' => site_trans('booking.activity_accommodation_luxury'),
        'A mix — we will confirm' => site_trans('booking.activity_accommodation_mix'),
    ];
    $transportOptions = [
        '4x4 with an English-speaking driver-guide' => site_trans('booking.activity_transport_4x4'),
        'Private transfer' => site_trans('booking.activity_transport_private'),
        'Fly where the route allows' => site_trans('booking.activity_transport_fly'),
        'Advise us' => site_trans('booking.activity_transport_advise'),
    ];
@endphp
<label>
    <span>{{ site_trans('booking.activity_date_start') }} *</span>
    <input type="date" name="activity_start" value="{{ $start }}" min="{{ now()->toDateString() }}" required>
</label>
<label>
    <span>{{ site_trans('booking.activity_date_end') }} *</span>
    <input type="date" name="activity_end" value="{{ $end }}" min="{{ $start ?: now()->toDateString() }}" required>
</label>
<label>
    <span>{{ site_trans('booking.activity_guests') }} *</span>
    <input type="number" name="guests" value="{{ $guests }}" min="1" max="40" required>
</label>
<label>
    <span>{{ site_trans('booking.activity_accommodation') }} *</span>
    <select name="accommodation_preference" required>
        <option value="">{{ site_trans('booking.activity_choose') }}</option>
        @foreach($accommodationOptions as $value => $label)
            <option value="{{ $value }}" @selected($accommodation === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="is-wide">
    <span>{{ site_trans('booking.activity_transport') }} *</span>
    <select name="transport_preference" required>
        <option value="">{{ site_trans('booking.activity_choose') }}</option>
        @foreach($transportOptions as $value => $label)
            <option value="{{ $value }}" @selected($transport === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="is-wide">
    <span>{{ site_trans('booking.activity_destinations') }} *</span>
    <textarea name="destinations" rows="3" required placeholder="{{ site_trans('booking.activity_destinations_hint') }}">{{ $destinations }}</textarea>
</label>
<fieldset class="is-wide">
    <legend>{{ site_trans('booking.activity_choices') }} *</legend>
    <div class="activity-show__choices">
        @foreach($activities as $option)
            <label>
                <input type="checkbox" name="activity_ids[]" value="{{ $option->id }}" @checked(in_array((int) $option->id, $selectedActivityIds, true))>
                <span>{{ $option->title }}</span>
            </label>
        @endforeach
    </div>
</fieldset>
<label class="is-wide">
    <span>{{ site_trans('booking.activity_notes') }}</span>
    <textarea name="notes" rows="4" placeholder="{{ site_trans('booking.activity_notes_hint') }}">{{ $notes }}</textarea>
</label>
