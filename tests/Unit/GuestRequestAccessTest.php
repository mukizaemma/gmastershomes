<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Services\GuestRequestService;
use Tests\TestCase;

class GuestRequestAccessTest extends TestCase
{
    public function test_a_pending_request_can_be_changed_before_the_start_date(): void
    {
        $booking = new Booking([
            'reservation_type' => 'room',
            'status' => 'pending',
            'checkin_date' => now()->addDays(3)->toDateString(),
        ]);

        $this->assertTrue(app(GuestRequestService::class)->canChange($booking));
    }

    public function test_a_request_cannot_be_cancelled_on_the_start_date(): void
    {
        $booking = new Booking([
            'reservation_type' => 'tour_activity',
            'status' => 'pending',
            'checkin_date' => now()->toDateString(),
        ]);

        $this->assertFalse(app(GuestRequestService::class)->canChange($booking));
    }

    public function test_a_cancelled_request_stays_closed(): void
    {
        $booking = new Booking([
            'reservation_type' => 'room',
            'status' => 'cancelled',
            'checkin_date' => now()->addDays(4)->toDateString(),
        ]);

        $this->assertFalse(app(GuestRequestService::class)->canChange($booking));
    }
}
