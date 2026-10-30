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

                    <p id="tour-request-error" class="recent-request__bad" hidden></p>
                    <div class="activity-show__submit {{ \App\Services\SiteNotificationMail::resendConfigured() ? '' : 'is-single' }}">
                        @if(\App\Services\SiteNotificationMail::resendConfigured())
                            <button type="submit" class="theme-btn btn-style fill">
                                <span><i class="fa-solid fa-envelope" aria-hidden="true"></i> {{ site_trans('booking.activity_submit') }}</span>
                            </button>
                        @endif
                        <a
                            id="tour-whatsapp-submit"
                            class="theme-btn btn-style fill activity-show__whatsapp"
                            href="#"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-no-spa-navigate
                            data-digits="{{ hotel_whatsapp_digits() }}"
                            data-hotel="{{ config('app.name') }}"
                            data-missing="{{ site_trans('booking.activity_whatsapp_missing') }}"
                            data-tours-required="{{ site_trans('booking.activity_tours_required') }}"
                        >
                            <span><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> {{ site_trans('booking.activity_submit_whatsapp') }}</span>
                        </a>
                    </div>
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
<script>
(function () {
    if (window.__tourWhatsappBound) {
        return;
    }
    window.__tourWhatsappBound = true;

    document.addEventListener('click', function (event) {
        var link = event.target.closest('#tour-whatsapp-submit');
        if (!link) {
            return;
        }

        var form = document.getElementById('tour-request-form');
        if (!form) {
            event.preventDefault();
            return;
        }

        var notice = document.getElementById('tour-request-error');
        if (notice) {
            notice.hidden = true;
            notice.textContent = '';
        }

        var boxes = form.querySelectorAll('input[name="activity_ids[]"]');
        var checked = form.querySelectorAll('input[name="activity_ids[]"]:checked');
        if (boxes.length && !checked.length) {
            if (notice) {
                notice.hidden = false;
                notice.textContent = link.getAttribute('data-tours-required') || 'Choose at least one tour.';
            }
            event.preventDefault();
            return;
        }

        if (!form.reportValidity()) {
            event.preventDefault();
            return;
        }

        var digits = (link.getAttribute('data-digits') || '').replace(/\D+/g, '');
        if (!digits) {
            window.alert(link.getAttribute('data-missing') || 'WhatsApp number is missing.');
            event.preventDefault();
            return;
        }

        function labelFor(el) {
            var wrap = el.closest('label');
            var title = wrap ? wrap.querySelector('span') : null;
            return title ? title.textContent.replace('*', '').trim() : '';
        }

        function valueFor(name) {
            var el = form.elements[name];
            if (!el || el.type === 'checkbox') {
                return '';
            }
            if (el.tagName === 'SELECT') {
                var option = el.options[el.selectedIndex];
                return option ? option.text.trim() : '';
            }
            return (el.value || '').trim();
        }

        var lines = ['Hello ' + (link.getAttribute('data-hotel') || '') + ',', '', 'I would like to request a tour.'];
        ['names', 'phone', 'email', 'activity_start', 'activity_end', 'guests', 'accommodation_preference', 'transport_preference', 'destinations', 'notes'].forEach(function (name) {
            var el = form.elements[name];
            var value = valueFor(name);
            if (!el || value === '') {
                return;
            }
            lines.push(labelFor(el) + ': ' + value);
        });

        var tours = [];
        checked.forEach(function (box) {
            var wrap = box.closest('label');
            var title = wrap ? wrap.querySelector('span') : null;
            if (title) {
                tours.push(title.textContent.trim());
            }
        });
        if (tours.length) {
            var legend = form.querySelector('.activity-show__choices') ? form.querySelector('fieldset legend') : null;
            lines.push((legend ? legend.textContent.replace('*', '').trim() : 'Tours') + ': ' + tours.join(', '));
        }

        link.href = 'https://wa.me/' + digits + '?text=' + encodeURIComponent(lines.join('\n'));

        var body = new FormData(form);
        body.append('submit_via', 'whatsapp');
        fetch(form.action, {
            method: 'POST',
            body: body,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).catch(function () {});
    });
})();
</script>
