<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\About;
use App\Models\User;

class HotelSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $superAdmin = User::query()->where('email', 'admin@iremetech.com')->first()
            ?? User::query()->orderBy('id')->first();

        $row = [
            'title' => 'GMasters Homes',
            'company' => 'GMasters Homes',
            'quote' => 'Stay a night, or stay a while',
            'address' => 'Kabeza, Kicukiro, Kigali, Rwanda',
            'phone' => null,
            'email' => null,
            'footer_delivered_by_enabled' => true,
            'footer_delivered_by_company' => 'Ireme Tech',
            'footer_delivered_by_url' => 'https://iremetech.com',
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('settings', 'translations_enabled')) {
            $row['translations_enabled'] = false;
        }

        if (Schema::hasColumn('settings', 'keywords')) {
            $row['keywords'] = 'GMasters Homes, guest house Kigali, Kabeza, bed and breakfast, long stay Kigali, airport guest house';
        }
        if (Schema::hasColumn('settings', 'meta_description')) {
            $row['meta_description'] = 'A quiet guest house in Kabeza, Kigali. Nine rooms for two guests. Breakfast with the bedrooms and with a short studio stay. Longer studio stays include the kitchen, water, electricity, internet and cleaning. Five minutes from the airport.';
        }
        if (Schema::hasColumn('settings', 'google_maps_embed_url')) {
            $row['google_maps_embed_url'] = 'https://maps.google.com/maps?q=Kabeza,+Kicukiro,+Kigali&z=15&output=embed';
        }

        $existing = DB::table('settings')->first();
        if ($existing) {
            $address = strtolower((string) ($existing->address ?? ''));
            $company = strtolower((string) ($existing->company ?? ''));
            $shouldRefresh = str_contains($address, 'kinigi')
                || str_contains($address, 'musanze')
                || str_contains($company, 'skojet')
                || trim((string) ($existing->address ?? '')) === '';
            if ($shouldRefresh) {
                DB::table('settings')->where('id', $existing->id)->update($row);
            } elseif (Schema::hasColumn('settings', 'translations_enabled')) {
                $patch = ['translations_enabled' => false];
                if (Schema::hasColumn('settings', 'meta_description') && isset($row['meta_description'])) {
                    $patch['meta_description'] = $row['meta_description'];
                }
                DB::table('settings')->where('id', $existing->id)->update($patch);
            }
        } else {
            $row['user_id'] = $superAdmin?->id;
            $row['created_at'] = now();
            DB::table('settings')->insert($row);
        }

        if (! Schema::hasTable('abouts')) {
            return;
        }

        $about = [
            'title' => 'A guest house in Kabeza',
            'subTitle' => 'Short stays, and room to stay longer',
            'founderDescription' => '<p>GMasters Homes is a quiet guest house in Kabeza, Kigali. It is a house, not a hotel: nine rooms, two guests in each, and a garden.</p><p>Four bedrooms in the main building share an equipped kitchen, with breakfast included. Four studios have a fridge, a television and their own kitchen. A short studio stay includes breakfast. On a longer studio stay, guests can prepare their own meals, with water, electricity, internet and cleaning included. One private bedroom stands apart, with breakfast, hot water and Wi-Fi.</p>',
            'mission' => 'A calm, direct stay in Kigali — easy to book, easy to arrive, and comfortable whether you are here for a night or for weeks.',
            'vision' => 'Guests who would rather come home to a garden than walk through a hotel lobby.',
            'storyDescription' => '<p>The airport is five minutes by car and about twenty-four minutes on foot. The Kigali Genocide Memorial in Gisozi is about twenty minutes by car. Kabeza market, a supermarket and food are nearby, and the house itself stays quiet.</p><p>Tours in Rwanda and Uganda — gorillas, chimpanzees, parks and volcanoes — can be arranged from the same website. The price is sent after the dates, the group and the preferences are shared.</p>',
            'user_id' => $superAdmin?->id,
            'updated_at' => now(),
        ];

        $current = About::query()->first();
        if ($current) {
            $plain = strtolower(strip_tags((string) $current->founderDescription));
            if ($plain === '' || str_contains($plain, 'skojet') || str_contains($plain, 'kinigi') || str_contains($plain, 'hotel')) {
                $current->fill($about);
                $current->save();
            } elseif (str_contains((string) $current->storyDescription, 'Explore Rwanda')) {
                $current->storyDescription = $about['storyDescription'];
                $current->save();
            }
        } else {
            $about['created_at'] = now();
            About::query()->create($about);
        }
    }
}
