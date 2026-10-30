<div class="public-livewire-page activity-show">
@php
    $coverUrl = filled($activity->cover_image) ? asset('storage/'.$activity->cover_image) : null;
    $selectedActivityIds = array_map('intval', (array) old('activity_ids', [$activity->id]));
    $imageUrl = function (?string $path): ?string {
        if (! filled($path)) {
            return null;
        }
        if (str_contains($path, '/')) {
            return asset('storage/'.$path);
        }

        return asset('storage/images/tour-activities/'.$path);
    };
@endphp

    <div class="container">
        <div class="activity-show__layout">
            <div class="activity-show__main">
                <p class="activity-show__eyebrow">{{ site_trans('nav.experiences') }}</p>
                <h1>{{ $activity->title }}</h1>
                @if(filled($activity->duration_label))
                    <p class="activity-show__duration">{{ $activity->duration_label }}</p>
                @endif

                @if($coverUrl)
                    <figure class="activity-show__cover">
                        <img src="{{ $coverUrl }}" alt="{{ $activity->title }}">
                    </figure>
                @endif

                @if(filled(trim(strip_tags($activity->description ?? ''))))
                    <div class="activity-show__copy">
                        {!! $activity->description !!}
                    </div>
                @endif

                @if($images->isNotEmpty())
                    <section class="activity-show__gallery" aria-label="{{ site_trans('booking.activity_gallery') }}">
                        <h2>{{ site_trans('booking.activity_gallery') }}</h2>
                        <div class="activity-show__grid">
                            @foreach($images as $img)
                                @php $imgUrl = $imageUrl($img->image); @endphp
                                @if($imgUrl)
                                    <figure>
                                        <img src="{{ $imgUrl }}" alt="{{ $img->caption ?: $activity->title }}" loading="lazy">
                                        @if(filled($img->caption))
                                            <figcaption>{{ $img->caption }}</figcaption>
                                        @endif
                                    </figure>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="activity-show__panel" id="activity-enquiry">
                @include('frontend.includes.recent-request-notice', ['formId' => 'tour-request-form'])
                <p class="activity-show__eyebrow">{{ site_trans('booking.activity_enquiry_eyebrow') }}</p>
                <h2>{{ site_trans('booking.activity_enquiry_title') }}</h2>
                <p class="activity-show__lead">{{ site_trans('booking.activity_enquiry_lead') }}</p>

                @if($errors->any())
                    <div class="activity-show__errors" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="tour-request-form" action="{{ route('tour-request.submit') }}" method="POST" data-no-spa-navigate>
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">

                    <div class="activity-show__fields">
                        <label>
                            <span>{{ site_trans('booking.form_name') }} *</span>
                            <input type="text" name="names" value="{{ old('names') }}" required autocomplete="name">
                        </label>
                        <label>
                            <span>{{ site_trans('booking.form_phone') }} *</span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                        </label>
                        <label class="is-wide">
                            <span>{{ site_trans('booking.form_email') }} *</span>
                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                        </label>
                        @include('frontend.includes.tour-request-fields', [
                            'activities' => $enquiryActivities,
                            'selectedActivityIds' => $selectedActivityIds,
                            'accommodation' => old('accommodation_preference', 'Mid-range lodges'),
                            'transport' => old('transport_preference', '4x4 with an English-speaking driver-guide'),
                            'destinations' => old('destinations'),
                            'start' => old('activity_start'),
                            'end' => old('activity_end'),
                            'guests' => old('guests', 2),
                            'notes' => old('notes'),
                        ])
                    </div>

                    <button type="submit" class="theme-btn btn-style fill">
                        <span>{{ site_trans('booking.activity_submit') }}</span>
                    </button>
                </form>
            </aside>
        </div>
    </div>

    @if($allActivities->isNotEmpty())
        <section class="activity-show__more">
            <div class="container">
                <p class="activity-show__eyebrow">{{ site_trans('nav.experiences') }}</p>
                <h2>{{ site_trans('booking.activity_more') }}</h2>
                <div class="activity-show__more-grid">
                    @foreach($allActivities as $other)
                        @php $otherCover = filled($other->cover_image) ? asset('storage/'.$other->cover_image) : null; @endphp
                        <a wire:navigate href="{{ localized_route('activity', ['slug' => $other->slug]) }}" class="activity-show__more-card">
                            @if($otherCover)
                                <img src="{{ $otherCover }}" alt="" loading="lazy">
                            @endif
                            <span>{{ $other->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
