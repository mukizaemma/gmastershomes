<?php

namespace Tests\Unit;

use App\Services\HostingBilling;
use App\Support\PlainPdf;
use Carbon\Carbon;
use Tests\TestCase;

class HostingRenewalTest extends TestCase
{
    public function test_the_hosting_year_starts_on_1_october(): void
    {
        $this->assertSame('2026-10-01', HostingBilling::periodStart(Carbon::parse('2026-10-30'))->toDateString());
        $this->assertSame('2026-10-01', HostingBilling::periodStart(Carbon::parse('2027-09-30'))->toDateString());
        $this->assertSame('2027-10-01', HostingBilling::periodStart(Carbon::parse('2027-10-01'))->toDateString());
    }

    public function test_reminder_days_match_the_expiry_calendar(): void
    {
        $end = Carbon::parse('2027-09-30');

        $this->assertSame(30, HostingBilling::daysUntil(Carbon::parse('2027-08-31'), $end));
        $this->assertSame(2, HostingBilling::daysUntil(Carbon::parse('2027-09-28'), $end));
        $this->assertSame(0, HostingBilling::daysUntil(Carbon::parse('2027-09-30'), $end));
        $this->assertSame(-1, HostingBilling::daysUntil(Carbon::parse('2027-10-01'), $end));
    }

    public function test_each_reminder_is_chosen_once(): void
    {
        $this->assertSame('month', HostingBilling::reminderStage(30, []));
        $this->assertSame('soon', HostingBilling::reminderStage(2, ['30' => true]));
        $this->assertSame('today', HostingBilling::reminderStage(0, ['30' => true, '2' => true]));
        $this->assertSame('expired', HostingBilling::reminderStage(-1, ['30' => true, '2' => true, '0' => true]));
        $this->assertNull(HostingBilling::reminderStage(-3, ['expired' => true]));
        $this->assertNull(HostingBilling::reminderStage(40, []));
    }

    public function test_a_missed_day_still_sends_the_closest_reminder(): void
    {
        $this->assertSame('month', HostingBilling::reminderStage(15, []));
        $this->assertSame('soon', HostingBilling::reminderStage(1, []));
        $this->assertSame('expired', HostingBilling::reminderStage(-4, []));
    }

    public function test_dollars_convert_at_the_stated_rate(): void
    {
        $this->assertSame(122400, HostingBilling::toRwf(80, 1530));
        $this->assertSame('$80.00 (122,400 RWF)', HostingBilling::formatBoth(80, 1530));
    }

    public function test_invoice_numbers_follow_the_ireme_sequence(): void
    {
        $this->assertSame('IREME/gmasters/PH001/2026', HostingBilling::invoiceNumber(Carbon::parse('2026-10-01')));
        $this->assertSame('IREME/gmasters/PH002/2027', HostingBilling::invoiceNumber(Carbon::parse('2027-10-01')));
    }

    public function test_invoice_download_is_a_pdf(): void
    {
        $pdf = PlainPdf::render([
            ['text' => 'GMasters Homes', 'size' => 16],
            ['text' => 'Hosting  $80.00'],
        ]);

        $this->assertStringStartsWith('%PDF-1.4', $pdf);
        $this->assertStringContainsString('GMasters Homes', $pdf);
    }
}
