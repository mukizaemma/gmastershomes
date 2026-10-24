<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->where('email', 'admin@iremetech.com')->value('id')
            ?? User::query()->orderBy('id')->value('id');

        if (! $userId) {
            return;
        }

        $shared = [
            'Hot water' => 'fa-shower',
            'Wi-Fi' => 'fa-wifi',
            'Breakfast' => 'fa-mug-hot',
        ];
        $bedroomOnly = [
            'Shared equipped kitchen' => 'fa-kitchen-set',
        ];
        $studioOnly = [
            'Equipped kitchen' => 'fa-kitchen-set',
            'Refrigerator' => 'fa-snowflake',
            'Television' => 'fa-tv',
            'Water included' => 'fa-tint',
            'Electricity included' => 'fa-bolt',
            'Cleaning included' => 'fa-broom',
        ];

        $amenities = [];
        foreach (array_merge($shared, $bedroomOnly, $studioOnly) as $title => $icon) {
            $amenities[$title] = Amenity::query()->firstOrCreate(
                ['title' => $title],
                ['icon' => $icon]
            )->id;
        }

        $rooms = [
            [
                'title' => 'Bedrooms',
                'slug' => 'bedrooms',
                'room_number' => 'BEDROOMS',
                'category' => 'bedroom',
                'number_of_rooms' => 4,
                'description' => '<p>Four bedrooms in the same building, each for two guests. They share an equipped kitchen, with hot water, Wi-Fi and breakfast included.</p><p>A good room for a night near the airport or a longer stay in Kigali, when you are happy to share the kitchen with the other bedrooms.</p>',
                'amenity_titles' => array_merge(array_keys($shared), array_keys($bedroomOnly)),
            ],
            [
                'title' => 'Studios',
                'slug' => 'studios',
                'room_number' => 'STUDIOS',
                'category' => 'studio',
                'number_of_rooms' => 4,
                'description' => '<p>Four studios, each for two guests. Every studio has a fridge, a television and an equipped kitchen, so it works for a short stay and for a much longer one.</p><p>A short stay includes breakfast, with hot water and Wi-Fi. On a longer stay, you can prepare your own meals in the kitchen. Water, electricity, internet and cleaning are included.</p>',
                'amenity_titles' => array_merge(['Hot water', 'Wi-Fi'], array_keys($studioOnly)),
            ],
            [
                'title' => 'Private bedroom',
                'slug' => 'private-bedroom',
                'room_number' => 'PRIVATE',
                'category' => 'private',
                'number_of_rooms' => 1,
                'description' => '<p>One private bedroom for two guests, set apart from the four bedrooms that share a kitchen. Breakfast, hot water and Wi-Fi are included.</p><p>A quiet room in the same gardened house, for a short visit or a longer one.</p>',
                'amenity_titles' => array_keys($shared),
            ],
        ];

        foreach ($rooms as $room) {
            $titles = $room['amenity_titles'];
            unset($room['amenity_titles']);

            $model = Room::query()->updateOrCreate(
                ['slug' => $room['slug']],
                array_merge($room, [
                    'room_type' => 'room',
                    'max_occupancy' => 2,
                    'bed_count' => 1,
                    'bed_type' => 'double',
                    'guests_included_in_price' => 2,
                    'status' => 'Active',
                    'room_status' => 'available',
                    'user_id' => $userId,
                    'price' => null,
                ])
            );

            $model->amenities()->sync(array_map(
                fn (string $title) => $amenities[$title],
                $titles
            ));
        }
    }
}
