{{-- Matches home "Our Hotel Rooms" cards — expects $room (Room model), optional $colClass --}}
<div class="{{ $colClass ?? 'col-12 col-md-6' }} room-listing-card">
    <article class="home-room-card">
        <a wire:navigate href="{{ localized_route('room', ['slug' => $room->slug]) }}" class="home-room-card__media">
            @if(filled($room->cover_image))
            <img src="{{ asset('storage/' . $room->cover_image) }}"
                alt="{{ $room->title }}"
                loading="lazy"
                width="800"
                height="480">
            @else
            <span class="gm-media-fallback" aria-hidden="true"></span>
            @endif
        </a>
        <div class="home-room-card__body">
            <div class="home-room-card__head">
                <a wire:navigate href="{{ localized_route('room', ['slug' => $room->slug]) }}" class="home-room-card__title">{{ $room->title }}</a>
                @if(hotel_has_public_rate($room))
                <div class="home-room-card__price">
                    <span class="home-room-card__price-from">{{ site_trans('room.from') }}</span>
                    <div class="home-room-card__price-line">
                        @include('frontend.includes.offer-price', ['room' => $room, 'setting' => $setting, 'variant' => 'card'])
                        <span class="home-room-card__price-unit">{{ site_trans('room.per_night') }}</span>
                    </div>
                </div>
                @else
                <div class="home-room-card__price">
                    <span class="home-room-card__price-from">{{ $room->category === 'studio' ? site_trans('home.meta_studio_short') : site_trans('home.meta_breakfast') }}</span>
                </div>
                @endif
            </div>
            @if($room->bed_type && isset(\App\Models\Room::BED_TYPES[$room->bed_type]))
                <p class="home-room-card__bed">{{ site_trans('room.bed_'.$room->bed_type) }}</p>
            @endif
            <p class="home-room-card__excerpt">
                {!! Str::words(strip_tags($room->description ?? ''), 28, '…') !!}
            </p>
            <div class="home-room-card__actions">
                <a wire:navigate href="{{ localized_route('room', ['slug' => $room->slug]) }}" class="theme-btn btn-style sm-btn border">
                    <span>View Details</span>
                </a>
                @include('frontend.includes.reservation-link', ['style' => 'sm-btn fill', 'room' => $room])
            </div>
        </div>
    </article>
</div>
