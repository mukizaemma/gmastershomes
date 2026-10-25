<div class="public-livewire-page">

@php
    $heroImage = '';
    $heroCaption = 'Our Team';
    $heroDescription = 'Meet the people behind your stay';
    $heroImage = page_hero_background($pageHero ?? null);
    if ($heroImage) {
        $heroCaption = $pageHero->caption ?? $heroCaption;
        $heroDescription = $pageHero->description ?? $heroDescription;
    }
@endphp
<div class="rts__section page__hero__height page__hero__bg {{ $heroImage ? '' : 'page__hero__bg--brand' }}" @if($heroImage) style="background-image: url({{ $heroImage }}); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page__hero__content">
                    <h1 class="wow fadeInUp" style="color: white; font-size: clamp(2.5rem, 5vw, 3.5rem); margin-bottom: 15px; font-family: 'Gilda Display', serif;">{{ $heroCaption }}</h1>
                    <p class="wow fadeInUp font-sm" style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">{{ $heroDescription }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rts__section section__padding" style="background: #ffffff; padding-top: 64px; padding-bottom: 80px;">
    <div class="container">
        @if($staff->isEmpty())
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <p class="text-muted" style="font-size: 1.1rem;">Our team section will be updated soon.</p>
                </div>
            </div>
        @else
            <div class="row g-4 justify-content-center">
                @foreach($staff as $member)
                    <div class="col-md-6 col-lg-4">
                        <div class="h-100" style="background: #fff; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid rgba(3, 86, 183, 0.08);">
                            <div style="aspect-ratio: 4/3; overflow: hidden; background: #f0f4f8;">
                                @if($member->image)
                                    <img src="{{ asset('storage/images/team/' . $member->image) }}" alt="{{ $member->names }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">No photo</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="h5 mb-1" style="font-family: 'Gilda Display', serif; color: #1a1a1a;">{{ $member->names }}</h3>
                                @if($member->position)
                                    <p class="text-primary small fw-semibold mb-3" style="color: #0356b7 !important;">{{ $member->position }}</p>
                                @endif
                                <div class="our-team__bio" style="font-size: 0.95rem; line-height: 1.7; color: #4a5568;">
                                    {!! $member->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
</div>
