<?php

namespace Database\Seeders;

use App\Models\TourActivity;
use App\Models\User;
use Illuminate\Database\Seeder;

class TourActivitySeeder extends Seeder
{
    public function run(): void
    {
        $addedBy = User::query()->where('email', 'admin@iremetech.com')->value('id')
            ?? User::query()->orderBy('id')->value('id');

        $activities = [
            [
                'title' => 'Kigali and the memorial',
                'slug' => 'kigali-memorial',
                'sort_order' => 1,
                'description' => '<p>A day in the city from Kabeza, including the Kigali Genocide Memorial in Gisozi, about twenty minutes from the house.</p><p>The pace follows your group and the time of day you choose.</p>',
            ],
            [
                'title' => 'Lake Kivu',
                'slug' => 'lake-kivu',
                'sort_order' => 2,
                'description' => '<p>The lake west of Kigali, for a day out or a night away from the house.</p><p>Tell us how many guests are travelling and whether you want to be back the same evening.</p>',
            ],
            [
                'title' => 'Akagera',
                'slug' => 'akagera',
                'sort_order' => 3,
                'description' => '<p>Savannah, lakes and wildlife in Akagera National Park, east of Kigali.</p><p>A day trip or an overnight, arranged around your dates.</p>',
            ],
            [
                'title' => 'Nyungwe',
                'slug' => 'nyungwe',
                'sort_order' => 4,
                'description' => '<p>The forest in the south-west: canopy, trails and time in Nyungwe.</p><p>This is usually more than a day. We plan it around the size of your group.</p>',
            ],
            [
                'title' => 'Volcanoes',
                'slug' => 'volcanoes',
                'sort_order' => 5,
                'description' => '<p>The north of Rwanda: volcano hikes and, when permits are already in place, time with the gorillas.</p><p>The days are arranged from Kigali for your group and the time you have.</p>',
            ],
            [
                'title' => 'Markets and coffee',
                'slug' => 'markets-and-coffee',
                'sort_order' => 6,
                'description' => '<p>A lighter day in Kigali: neighbourhoods, coffee and markets, easy to start from Kabeza.</p><p>Morning, afternoon or a full day — tell us which suits the group.</p>',
            ],
        ];

        $slugs = [];
        foreach ($activities as $activity) {
            $slugs[] = $activity['slug'];
            TourActivity::query()->updateOrCreate(
                ['slug' => $activity['slug']],
                array_merge($activity, [
                    'status' => 'Active',
                    'added_by' => $addedBy,
                    'cover_image' => null,
                ])
            );
        }

        TourActivity::query()->whereNotIn('slug', $slugs)->update(['status' => 'Inactive']);
    }
}
