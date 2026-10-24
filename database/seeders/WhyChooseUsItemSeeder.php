<?php

namespace Database\Seeders;

use App\Models\WhyChooseUsItem;
use Illuminate\Database\Seeder;

class WhyChooseUsItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Nine rooms, two guests each',
                'description' => 'Four bedrooms, four studios and one private bedroom. The house stays small on purpose.',
                'icon' => 'fa-solid fa-bed',
                'sort_order' => 1,
            ],
            [
                'title' => 'A night or a longer stay',
                'description' => 'Come for the airport, a weekend in Kigali, or weeks with a kitchen of your own.',
                'icon' => 'fa-solid fa-calendar-days',
                'sort_order' => 2,
            ],
            [
                'title' => 'Breakfast, or your own kitchen',
                'description' => 'Bedrooms include breakfast. A short studio stay includes breakfast. On a longer studio stay, guests can cook in the kitchen, with water, electricity, internet and cleaning included.',
                'icon' => 'fa-solid fa-mug-hot',
                'sort_order' => 3,
            ],
            [
                'title' => 'A quiet garden in Kabeza',
                'description' => 'The market, a supermarket and food are nearby. The house itself is calm.',
                'icon' => 'fa-solid fa-leaf',
                'sort_order' => 4,
            ],
            [
                'title' => 'Five minutes from the airport',
                'description' => 'Five minutes by car, about twenty-four minutes on foot, from Kigali International Airport.',
                'icon' => 'fa-solid fa-plane',
                'sort_order' => 5,
            ],
            [
                'title' => 'Twenty minutes to Gisozi',
                'description' => 'The Kigali Genocide Memorial is a short drive, and the rest of the city is easy to reach.',
                'icon' => 'fa-solid fa-landmark',
                'sort_order' => 6,
            ],
            [
                'title' => 'Book the house directly',
                'description' => 'Tell us your dates and your room. We confirm the stay with you. Nothing is charged on the website.',
                'icon' => 'fa-solid fa-calendar-check',
                'sort_order' => 7,
            ],
        ];

        $kept = [];
        foreach ($items as $row) {
            $item = WhyChooseUsItem::updateOrCreate(
                ['sort_order' => $row['sort_order']],
                [
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'icon' => $row['icon'],
                ]
            );
            $kept[] = $item->id;
        }

        WhyChooseUsItem::query()->whereNotIn('id', $kept)->delete();
    }
}
