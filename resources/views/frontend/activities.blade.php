<div class="public-livewire-page">

@php
    $heroImage = '';
    $heroCaption = site_trans('nav.experiences');
    $heroDescription = site_trans('home.experiences_lead');

    $heroImage = page_hero_background($pageHero ?? null);
    if ($heroImage) {
        $heroCaption = $pageHero->caption ?? $heroCaption;
        $heroDescription = $pageHero->description ?? $heroDescription;
    }
@endphp

    <div class="rts__section page__hero__height page__hero__bg {{ $heroImage ? '' : 'page__hero__bg--brand' }}" @if($heroImage) style="background-image: url({{ $heroImage }}); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-12">
                    <div class="page__hero__content">
                        <h1 class="wow fadeInUp">{{ $heroCaption }}</h1>
                        <p class="wow fadeInUp font-sm">{{ $heroDescription }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rts__section section__padding">
        <div class="container">
            <div class="row g-4">
                @forelse ($activities as $activity)
                    @php
                        $coverUrl = filled($activity->cover_image)
                            ? asset('storage/' . $activity->cover_image)
                            : null;
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="h-100 d-flex flex-column rounded-3 overflow-hidden shadow-sm border" style="background: #fff; border-color: #eee !important;">
                            <div class="flex-grow-0">
                                <a wire:navigate href="{{ localized_route('activity', ['slug' => $activity->slug]) }}">
                                    @if(filled($activity->cover_image))
                                    <img src="{{ $coverUrl }}" alt="{{ $activity->title }}" loading="lazy" style="width: 100%; height: 260px; object-fit: cover;">
                                    @else
                                    <span class="gm-media-fallback" style="display:block;height:180px;" aria-hidden="true"></span>
                                    @endif
                                </a>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                @if(filled($activity->duration_label))
                                    <p class="text-uppercase small mb-2" style="letter-spacing: 0.14em;">{{ $activity->duration_label }}</p>
                                @endif
                                <a wire:navigate href="{{ localized_route('activity', ['slug' => $activity->slug]) }}" class="h5 mb-2 text-dark text-decoration-none">{{ $activity->title }}</a>
                                <div class="flex-grow-1 mb-3">
                                    <p class="font-sm text-muted mb-0">{{ $activity->summary ?: \Illuminate\Support\Str::limit(trim(strip_tags($activity->description ?? '')), 140) }}</p>
                                </div>
                                <a wire:navigate href="{{ localized_route('activity', ['slug' => $activity->slug]) }}" class="theme-btn btn-style fill align-self-start">
                                    <span>{{ site_trans('home.view_details') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted mb-0">No tour activities available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
