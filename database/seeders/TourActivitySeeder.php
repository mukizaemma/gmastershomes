<?php

namespace Database\Seeders;

use App\Models\About;
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
            $this->tour(
                '2 Days Gorilla Trekking — Bwindi from Kigali',
                '2-day-gorilla-trekking-bwindi-from-kigali',
                1,
                '2 days',
                'Two days from Kigali to Bwindi for mountain gorillas, with the Kigali Genocide Memorial on the way out. Mid-range lodging. The price is sent after your request.',
                '<p>Fly out of Kigali, or start from your hotel. The guide meets you, visits the Kigali Genocide Memorial, then drives you to Bwindi Impenetrable National Park. After the trek you return to Kigali the same day.</p>',
                [
                    ['Day 1 — Kigali Genocide Memorial and transfer to Bwindi', 'On arrival at Kigali Airport, or from your hotel in Kigali, the safari guide meets you. Visit the Kigali Genocide Memorial, then transfer to Bwindi. Dinner and overnight at Bakiga Lodge (mid-range).'],
                    ['Day 2 — Gorilla trekking and return to Kigali', 'After an early breakfast, briefing is at 8:00 and the trek starts at 9:00 with Uganda Wildlife Authority staff. Carry a rain jacket, drinking water, snacks and jungle boots. The guides lead you to the gorilla group you are booked for. Take your lunch box. Tracking takes between 2 and 6 hours. Afterwards you drive back to Kigali and are dropped at a place of your choice, or at the airport for a late departure.'],
                ],
                [
                    'Transportation in a 4-wheel-drive vehicle',
                    'Full-board accommodation',
                    'One gorilla permit, secured in advance',
                    'Kigali Genocide Memorial',
                    'Park fees',
                    'An English-speaking driver-guide',
                    'Meals en route',
                    'Airport drop-off',
                ],
                ['Visas, drinks, laundry, tips, telephone calls, and the porter fee while trekking.']
            ),
            $this->tour(
                '11 Days Uganda Super Safari',
                '11-day-uganda-super-safari',
                2,
                '11 days',
                'Ziwa rhinos, Murchison Falls, Kibale chimpanzees, Queen Elizabeth, Bwindi gorillas and Lake Mburo, in mid-range lodges. The price is sent after your request.',
                '<p>A longer Uganda circuit from Entebbe or Kampala: rhinos, the Nile, chimpanzees, the Kazinga Channel, gorillas in Bwindi, and a walking safari at Lake Mburo. Lodging is mid-range. Gorilla permits for Buhoma or Ruhija need to be booked as soon as the dates are known.</p>',
                [
                    ['Day 1 — Kampala city tour', 'Pickup from the airport and a city tour: Kasubi Tombs, Kabaka’s Palace, the Uganda Museum, the Baháʼí Temple and the Gaddafi Mosque. Dinner and overnight at Cassia Lodge (mid-range).'],
                    ['Day 2 — Ziwa rhinos, then Murchison Falls', 'Drive to Ziwa Rhino Sanctuary, track the rhinos on foot, then continue to Murchison Falls National Park for an evening game drive. Dinner and overnight at Fort Murchison (mid-range).'],
                    ['Day 3 — Game drive, Nile cruise and the top of the falls', 'An early game drive for elephants, antelope, giraffes, warthogs, buffalo and, when they are out, lions or leopards. Later, a launch cruise on the Nile and a visit to the top of the falls. Dinner and overnight at Fort Murchison (mid-range).'],
                    ['Day 4 — Kibale Forest and the Bigodi walk', 'Drive to Kibale Forest for an evening nature walk in Bigodi. Dinner and overnight at Chimpanzee Forest Lodge (mid-range).'],
                    ['Day 5 — Chimpanzee tracking, then Queen Elizabeth', 'After briefing and registration at the Uganda Wildlife Authority office, track chimpanzees for 2 to 5 hours. Continue to Queen Elizabeth National Park in time for a short evening game drive when the road allows. Dinner and overnight at Enganzi Game Lodge (mid-range).'],
                    ['Day 6 — Game drive and the Kazinga Channel', 'A morning game drive for elephants, lions, leopards, warthogs and antelope. Lunch and a rest at the lodge, then a two-hour boat cruise on the Kazinga Channel for hippos and birds. Dinner and overnight at Enganzi Game Lodge (mid-range).'],
                    ['Day 7 — Ishasha and on to Bwindi', 'Drive to Bwindi through the Ishasha sector, where tree-climbing lions are sometimes seen, and through the Kigezi highlands. Dinner and overnight at Bakiga Lodge (mid-range).'],
                    ['Day 8 — Gorilla trekking', 'Briefing and registration at the park headquarters, with a packed lunch. The trek takes 2 to 6 hours. Dinner and overnight at Bakiga Lodge (mid-range).'],
                    ['Day 9 — Lake Mburo', 'Drive to Lake Mburo National Park for an evening game drive. Dinner and overnight at Rwakobo Rock (mid-range).'],
                    ['Day 10 — Walking safari, boat cruise and Entebbe', 'A morning walking safari for giraffe, antelope, buffalo and zebra, then a boat trip on Lake Mburo. Transfer to Entebbe Airport for a late flight, with a stop at the equator for a photograph.'],
                ],
                [
                    'Transportation in a 4x4, with fuel',
                    'Airport transfers',
                    'Full-board accommodation',
                    'All game drives',
                    'Two launch cruises',
                    'Ziwa Rhino Sanctuary',
                    'Visit to the top of Murchison Falls',
                    'Bigodi swamp walk',
                    'One gorilla permit, secured in advance',
                    'One chimpanzee permit for Kibale Forest',
                    'Lake Mburo walking safari and boat trip',
                    'An English-speaking tour guide',
                    'Government taxes',
                ],
                ['Visas, tips, airtime, and other personal expenses.']
            ),
            $this->tour(
                '7 Days Uganda Super Safari',
                '7-day-uganda-super-safari',
                3,
                '7 days',
                'From Kigali: a volcano hike in Mgahinga, gorillas in Bwindi, Queen Elizabeth and chimpanzees in Kibale, ending in Entebbe. Mid-range lodges. The price is sent after your request.',
                '<p>The week starts before dawn in Kigali, climbs one of the Mgahinga volcanoes, then continues through Bwindi, Queen Elizabeth and Kibale. The safari ends at Entebbe Airport.</p>',
                [
                    ['Day 1 — Mgahinga volcano, then Bwindi', 'Wake at 3:00 with a packed breakfast and drive from Kigali to Mgahinga National Park. Hike Sabyinyo, Muhabura or Gahinga, about 7 to 8 hours, then continue to Bwindi. Dinner and overnight at Rushaga Gorilla Camp, Buhoma Heaven, or Bakiga Lodge.'],
                    ['Day 2 — Gorilla trekking', 'After breakfast, with a packed lunch, briefing and registration at the Uganda Wildlife Authority office in Bwindi. The trek starts at 9:00 and lasts 2 to 6 hours, depending on where the gorillas are. Dinner and overnight at Rushaga Gorilla Camp.'],
                    ['Day 3 — Queen Elizabeth National Park', 'Drive to Queen Elizabeth and arrive in time for an evening game drive. Dinner and overnight at Enshama Game Lodge.'],
                    ['Day 4 — Game drive and the Kazinga Channel', 'A morning game drive for elephants, lions, leopards, warthogs and antelope. Lunch and a rest, then a two-hour boat cruise on the Kazinga Channel for hippos and birds. Dinner and overnight at Enshama Game Lodge.'],
                    ['Day 5 — Kibale and the Bigodi walk', 'A relaxed morning, then the drive to Kibale Forest with a photo stop at the equator at Kikorongo. In the evening, the Bigodi nature walk. Dinner and overnight at Kibale Forest Camp.'],
                    ['Day 6 — Chimpanzee habituation', 'At 5:30, after breakfast, briefing and registration at the park headquarters. The chimpanzee habituation experience follows the group as they leave their nests to feed, travel and play. You spend about 4 hours with them after they are found. Dinner and overnight at Kibale Forest Camp.'],
                    ['Day 7 — Entebbe Airport', 'After an early breakfast, transfer to Entebbe Airport for a late departure.'],
                ],
                [
                    'Transportation in a 4x4, with fuel',
                    'Airport transfers',
                    'Full-board accommodation',
                    'One volcano hike',
                    'One gorilla permit for Uganda',
                    'Bigodi swamp walk',
                    'One chimpanzee habituation permit for Kibale Forest',
                    'One launch cruise',
                    'Kigali city tour',
                    'An English-speaking tour guide',
                    'Government taxes',
                ],
                ['Visas, tips, airtime, and other personal expenses.']
            ),
            $this->tour(
                '6 Days Chimpanzees, Wildlife, Gorillas and Golden Monkeys',
                '6-day-chimpanzees-wildlife-gorillas-golden-monkeys',
                4,
                '6 days',
                'From Kigali through Kibale, Queen Elizabeth, golden monkeys in Mgahinga and gorillas in Bwindi, then back to Kigali. Mid-range lodges. The price is sent after your request.',
                '<p>Chimpanzees in Kibale, wildlife and a Kazinga cruise in Queen Elizabeth, golden monkeys in Mgahinga, and gorillas in Bwindi. The safari starts in Kigali and ends at Kigali Airport.</p>',
                [
                    ['Day 1 — Kigali to Kibale', 'After an early breakfast the guide collects you from the airport or from your hotel in Kigali and drives to Kibale National Park. Lunch is en route. Meals and overnight at Turaco Treetops.'],
                    ['Day 2 — Chimpanzees and Bigodi', 'After breakfast, briefing at the Uganda Wildlife Authority office, then 1 to 4 hours tracking chimpanzees. In the evening, a cultural walk at Bigodi swamp. Meals and overnight at Turaco Treetops.'],
                    ['Day 3 — Queen Elizabeth and the Kazinga Channel', 'A relaxed morning, then the drive to Queen Elizabeth National Park for an afternoon boat cruise on the Kazinga Channel. Dinner and overnight at Twin Lakes Safari Lodge.'],
                    ['Day 4 — Game drive, then toward Mgahinga', 'An early game drive, including the lakeshore where antelope gather, with a chance of lions, leopards and elephants. Continue toward Mgahinga. Dinner and overnight at Kisoro Travellers Rest (mid-range).'],
                    ['Day 5 — Golden monkeys, then Bwindi', 'Track golden monkeys in Mgahinga for 2 to 4 hours, then transfer to Bwindi Impenetrable National Park. Dinner and overnight in Buhoma.'],
                    ['Day 6 — Gorilla trekking and return to Kigali', 'With a packed lunch, briefing and registration, then a gorilla trek of 1 to 6 hours. Afterwards, drive to Kigali Airport for the departure flight.'],
                ],
                [
                    'Transportation in a 4x4',
                    'Full-board accommodation',
                    'Bigodi cultural walk',
                    'A launch cruise on the Kazinga Channel',
                    'One chimpanzee permit for Kibale Forest',
                    'One gorilla permit per person, secured and paid in advance',
                    'One golden monkey permit',
                    'A full-time English-speaking driver-guide',
                    'Lunches en route',
                    'A game drive',
                    'Airport transfers',
                ],
                ['Visas, drinks, laundry, tips, telephone calls, personal insurance and tobacco.']
            ),
        ];

        $slugs = [];
        foreach ($activities as $activity) {
            $slugs[] = $activity['slug'];
            TourActivity::query()->updateOrCreate(
                ['slug' => $activity['slug']],
                array_merge($activity, [
                    'status' => 'Active',
                    'added_by' => $addedBy,
                ])
            );
        }

        TourActivity::query()->whereNotIn('slug', $slugs)->update(['status' => 'Inactive']);

        $about = About::query()->first();
        if ($about && str_contains((string) $about->storyDescription, 'Explore Rwanda')) {
            $about->storyDescription = str_replace(
                'Explore Rwanda — city days, lakes, parks and the volcanoes — can be arranged from the same website, around your dates and your group.',
                'Tours in Rwanda and Uganda — gorillas, chimpanzees, parks and volcanoes — can be arranged from the same website. The price is sent after the dates, the group and the preferences are shared.',
                (string) $about->storyDescription
            );
            $about->save();
        }
    }

    /**
     * @param  list<array{0: string, 1: string}>  $days
     * @param  list<string>  $included
     * @param  list<string>  $excluded
     * @return array<string, mixed>
     */
    private function tour(string $title, string $slug, int $order, string $duration, string $summary, string $intro, array $days, array $included, array $excluded): array
    {
        $dayHtml = '';
        foreach ($days as [$heading, $body]) {
            $dayHtml .= '<h3>'.e($heading).'</h3><p>'.e($body).'</p>';
        }

        $includeHtml = '';
        foreach ($included as $item) {
            $includeHtml .= '<li>'.e($item).'</li>';
        }

        $excludeHtml = '';
        foreach ($excluded as $item) {
            $excludeHtml .= '<li>'.e($item).'</li>';
        }

        return [
            'title' => $title,
            'slug' => $slug,
            'sort_order' => $order,
            'duration_label' => $duration,
            'summary' => $summary,
            'description' => $intro
                .'<p>The safari price is not listed here. It is sent after you share the dates, the number of people, the tours, the accommodation and the transport you want.</p>'
                .'<h2>Itinerary</h2>'.$dayHtml
                .'<h2>Included</h2><ul>'.$includeHtml.'</ul>'
                .'<h2>Not included</h2><ul>'.$excludeHtml.'</ul>',
        ];
    }
}
