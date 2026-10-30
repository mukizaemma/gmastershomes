<div class="livewire-home-page skojet-home">
{{-- 1. Full-screen cinematic hero --}}
@include('frontend.includes.slides')

{{-- 2. Invitation after the hero --}}
<section class="rts__section skojet-why section__padding" id="why-skojet" aria-labelledby="why-skojet-heading">
    <div class="container">
        <div class="skojet-why__layout">
            <div class="skojet-why__story">
                <span class="skojet-intro__eyebrow">{{ site_trans('home.why_eyebrow') }}</span>
                <h2 id="why-skojet-heading" class="skojet-why__title">{{ site_trans('home.why_title') }}</h2>
                <p class="skojet-why__lead">{{ site_trans('home.why_lead') }}</p>
                <p class="skojet-why__note">{{ site_trans('home.invite_note') }}</p>
                <a href="#signature-suite" data-no-spa-navigate class="skojet-why__link" onclick="var el=document.getElementById('signature-suite'); if(el){el.scrollIntoView({behavior:'smooth',block:'start'});} return false;">
                    <span>{{ site_trans('home.invite_cta') }}</span>
                    <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
            <div class="skojet-stats" data-skojet-counters>
                @foreach ([
                    ['value' => '9', 'count' => true, 'label' => site_trans('home.stat_suites')],
                    ['value' => '2', 'count' => true, 'label' => site_trans('home.stat_guests')],
                    ['value' => '5', 'count' => true, 'label' => site_trans('home.stat_airport')],
                    ['value' => site_trans('home.stat_board_mark'), 'count' => false, 'label' => site_trans('home.stat_board')],
                ] as $i => $stat)
                <div class="skojet-stat" style="animation-delay: {{ $i * 0.08 }}s">
                    @if($stat['count'])
                    <span class="skojet-stat__value" data-count="{{ $stat['value'] }}" data-suffix="">{{ $stat['value'] }}</span>
                    @else
                    <span class="skojet-stat__value skojet-stat__value--text">{{ $stat['value'] }}</span>
                    @endif
                    <span class="skojet-stat__label">{{ $stat['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 3. Suites — first image left, second image right --}}
@php
    $featuredSuites = ($rooms ?? collect())->take(2)->values();
@endphp
@foreach ($featuredSuites as $suiteIndex => $featuredSuite)
@php
    $suitePlain = trim(preg_replace('/\s+/', ' ', strip_tags($featuredSuite->description ?? '')));
    $suiteBlurb = mb_strlen($suitePlain) > 24
        ? \Illuminate\Support\Str::words($suitePlain, 42, '…')
        : site_trans('home.signature_fallback');
    $featureMeta = match ($featuredSuite->category) {
        'studio' => [site_trans('home.meta_sleeps'), site_trans('home.meta_studio_short'), site_trans('home.meta_studio_long')],
        'private' => [site_trans('home.meta_sleeps'), site_trans('home.meta_breakfast'), site_trans('home.meta_private')],
        default => [site_trans('home.meta_sleeps'), site_trans('home.meta_breakfast'), site_trans('home.meta_bedroom_kitchen')],
    };
    $featureNight = hotel_nightly_offer($featuredSuite, null, null, $setting ?? null);
@endphp
<section class="skojet-signature {{ $suiteIndex % 2 === 1 ? 'skojet-signature--flip' : '' }}" @if($suiteIndex === 0) id="signature-suite" @endif aria-labelledby="signature-suite-heading-{{ $featuredSuite->id }}">
    <div class="skojet-signature__grid">
        <div class="skojet-signature__media">
            @if(filled($featuredSuite->cover_image))
            <img src="{{ optimized_image($featuredSuite->cover_image, 1280) }}"
                 alt="{{ $featuredSuite->title }}"
                 loading="lazy"
                 decoding="async"
                 width="1200"
                 height="900">
            @else
            <div class="gm-media-fallback" role="img" aria-label="{{ $featuredSuite->title }}"></div>
            @endif
        </div>
        <div class="skojet-signature__panel">
            <div class="skojet-signature__heading">
                <h2 id="signature-suite-heading-{{ $featuredSuite->id }}" class="skojet-intro__title">{{ $featuredSuite->title }}</h2>
                <p class="skojet-signature__price">
                    @if((float) ($featureNight['sale'] ?? 0) > 0)
                    @include('frontend.includes.offer-price', ['room' => $featuredSuite, 'setting' => $setting, 'variant' => 'signature'])
                    <span class="skojet-signature__price-unit">{{ site_trans('home.per_night') }}</span>
                    @else
                    <span class="skojet-signature__price-unit">{{ $featuredSuite->category === 'studio' ? site_trans('home.meta_studio_short') : site_trans('home.meta_breakfast') }}</span>
                    @endif
                </p>
            </div>
            <p class="skojet-intro__lead">{{ $suiteBlurb }}</p>
            <div class="skojet-signature__meta">
                @foreach($featureMeta as $metaLabel)
                <span>{{ $metaLabel }}</span>
                @endforeach
            </div>
            <div class="skojet-signature__actions">
                <a wire:navigate href="{{ localized_route('room', ['slug' => $featuredSuite->slug]) }}" class="theme-btn btn-style border skojet-signature__details">
                    <span>{{ site_trans('home.view_details') }}</span>
                </a>
                <a wire:navigate href="{{ localized_route('connect', ['room' => $featuredSuite->id]) }}" class="theme-btn btn-style fill skojet-signature__book">
                    <span>{{ site_trans('home.book_this_suite') }}</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endforeach

{{-- 4. Homepage gallery — first 6 images, 3 columns --}}
@if(($homeGallery ?? collect())->isNotEmpty())
<section class="rts__section section__padding skojet-home-gallery" id="home-gallery" aria-labelledby="home-gallery-heading">
    <div class="container">
        <header class="skojet-intro">
            <span class="skojet-intro__eyebrow">{{ site_trans('home.gallery_eyebrow') }}</span>
            <h2 id="home-gallery-heading" class="skojet-intro__title">{{ site_trans('home.experiences_title') }}</h2>
        </header>
        <div class="skojet-home-gallery__grid">
            @foreach($homeGallery as $image)
                @continue($image->publicImageUrl() === '')
                <a wire:navigate href="{{ localized_route('gallery') }}" class="skojet-home-gallery__item">
                    <img src="{{ $image->publicImageUrl() }}" alt="{{ $image->caption ?: site_trans('home.gallery_eyebrow') }}" loading="lazy" decoding="async" fetchpriority="low" width="960" height="640">
                </a>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a wire:navigate href="{{ localized_route('gallery') }}" class="theme-btn btn-style border">
                <span>{{ site_trans('home.gallery_view') }}</span>
            </a>
        </div>
    </div>
</section>
@endif

{{-- 5. Location — Kinigi / Musanze / Volcanoes --}}
<section class="rts__section skojet-location section__padding" id="location" aria-labelledby="location-heading">
    <div class="container">
        <div class="skojet-location__grid">
            <div>
                <header class="skojet-intro" style="margin:0 0 0;text-align:left;max-width:none">
                    <span class="skojet-intro__eyebrow">{{ site_trans('home.location_eyebrow') }}</span>
                    <h2 id="location-heading" class="skojet-intro__title" style="text-align:left">{{ site_trans('home.location_title') }}</h2>
                    <p class="skojet-intro__lead" style="text-align:left">{{ site_trans('home.location_lead') }}</p>
                </header>
                <ul class="skojet-location__pins">
                    <li>
                        <i class="fa-solid fa-plane" aria-hidden="true"></i>
                        <div>
                            <strong>{{ site_trans('home.pin_volcanoes_title') }}</strong>
                            <span>{{ site_trans('home.pin_volcanoes_text') }}</span>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-landmark" aria-hidden="true"></i>
                        <div>
                            <strong>{{ site_trans('home.pin_rdb_title') }}</strong>
                            <span>{{ site_trans('home.pin_rdb_text') }}</span>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-leaf" aria-hidden="true"></i>
                        <div>
                            <strong>{{ site_trans('home.pin_kinigi_title') }}</strong>
                            <span>{{ site_trans('home.pin_kinigi_text') }}</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="skojet-location__aside">
                <a wire:navigate href="{{ localized_route('activities') }}" class="gm-explore-card {{ filled($toursHeroImage ?? null) ? 'has-hero' : '' }}" @if(filled($toursHeroImage ?? null)) style="--gm-explore-image: url('{{ $toursHeroImage }}')" @endif>
                    <span class="skojet-intro__eyebrow">{{ site_trans('nav.experiences') }}</span>
                    <strong>{{ site_trans('home.explore_card_title') }}</strong>
                    <span>{{ site_trans('home.explore_card_lead') }}</span>
                    <em>{{ site_trans('home.view_all_activities') }}</em>
                </a>
            </div>
        </div>
    </div>
</section>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('[data-skojet-counters]');
    if (!root || !('IntersectionObserver' in window)) return;

    var animated = false;
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting || animated) return;
            animated = true;
            root.querySelectorAll('[data-count]').forEach(function (el) {
                var target = parseInt(el.getAttribute('data-count'), 10) || 0;
                var suffix = el.getAttribute('data-suffix') || '';
                var start = 0;
                var duration = 1200;
                var startTime = null;
                function step(ts) {
                    if (!startTime) startTime = ts;
                    var progress = Math.min((ts - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(start + (target - start) * eased) + suffix;
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
            observer.disconnect();
        });
    }, { threshold: 0.35 });
    observer.observe(root);
});
</script>
@endpush
