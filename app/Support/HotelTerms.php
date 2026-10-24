<?php

namespace App\Support;

class HotelTerms
{
    public static function html(): string
    {
        return <<<'HTML'
<div class="terms-doc">
<section class="terms-doc__block">
<h2>The stay</h2>
<ul>
<li>GMasters Homes is a guest house in Kabeza, Kigali.</li>
<li>Each room is arranged for <strong>two guests</strong>.</li>
<li>Breakfast is included with the bedrooms and the private bedroom, and with a short studio stay.</li>
<li>On a longer studio stay, guests can prepare their own meals in the in-room kitchen. Water, electricity, internet and cleaning are included.</li>
</ul>
</section>
<section class="terms-doc__block">
<h2>Short and longer stays</h2>
<ul>
<li>Rooms can be booked for a night or for a longer stay.</li>
<li>Studios suit a short stay with breakfast, or a longer stay spent cooking in the kitchen.</li>
<li>The nightly rate is confirmed with the reservation. Nothing is charged on the website.</li>
</ul>
</section>
<section class="terms-doc__block terms-doc__block--wide">
<h2>Check-in / Check-out</h2>
<ul>
<li>Check-in: <strong>2:00 p.m.</strong></li>
<li>Check-out: <strong>11:00 a.m.</strong></li>
<li>Earlier arrival or a later departure can be arranged when the room is free.</li>
</ul>
</section>
<section class="terms-doc__block">
<h2>Booking</h2>
<ul>
<li>A request is confirmed when the house accepts it.</li>
<li>Payment details are shared when the stay is confirmed.</li>
<li>Please tell us as soon as your dates change.</li>
</ul>
</section>
<section class="terms-doc__block">
<h2>The house</h2>
<ul>
<li>This is a quiet guest house. Please keep noise down, especially in the evening.</li>
<li>Hot water and Wi-Fi are part of the stay.</li>
<li>Guests look after their own belongings.</li>
</ul>
</section>
<section class="terms-doc__block terms-doc__block--wide">
<h2>Other terms</h2>
<ul>
<li>If the reserved room is unavailable, another room in the house may be offered.</li>
<li>Changes to a reservation should be sent in writing, by email or WhatsApp.</li>
<li><strong>Jurisdiction:</strong> Kigali, Rwanda.</li>
</ul>
</section>
<section class="terms-doc__close">
<p class="terms-doc__disclaimer"><strong>Disclaimer:</strong> Terms &amp; Conditions are subject to change without prior notice.</p>
<p class="terms-doc__sign">GMasters Homes</p>
</section>
</div>
HTML;
    }

    public static function isStale(?string $html): bool
    {
        $html = (string) $html;

        if (trim(strip_tags($html)) === '') {
            return true;
        }

        return str_contains($html, 'terms-doc__address')
            || str_contains($html, 'terms-doc__contacts')
            || str_contains($html, 'with every stay');
    }
}
