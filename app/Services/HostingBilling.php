<?php

namespace App\Services;

use App\Mail\HostingRenewalMail;
use App\Models\HostingInvoice;
use App\Models\HostingRate;
use App\Support\PlainPdf;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class HostingBilling
{
    public static function calendarToday(): Carbon
    {
        return Carbon::parse(now(config('hosting.timezone', 'Africa/Kigali'))->toDateString())->startOfDay();
    }

    public static function periodStart(CarbonInterface $date): Carbon
    {
        $year = $date->month >= 10 ? $date->year : $date->year - 1;

        return Carbon::create($year, 10, 1)->startOfDay();
    }

    public static function daysUntil(CarbonInterface $today, CarbonInterface $periodEnd): int
    {
        $start = Carbon::parse($today->toDateString())->startOfDay();
        $end = Carbon::parse($periodEnd->toDateString())->startOfDay();

        return (int) $start->diffInDays($end, false);
    }

    /**
     * @param  array{30?: bool, 2?: bool, 0?: bool, expired?: bool}  $sent
     */
    public static function reminderStage(int $days, array $sent): ?string
    {
        if ($days < 0) {
            return ! empty($sent['expired']) ? null : 'expired';
        }

        if ($days === 0) {
            return ! empty($sent['0']) ? null : 'today';
        }

        if ($days <= 2) {
            return ! empty($sent['2']) ? null : 'soon';
        }

        if ($days <= 30) {
            return ! empty($sent['30']) ? null : 'month';
        }

        return null;
    }

    public static function formatMoney(mixed $amount): string
    {
        return '$'.number_format((float) $amount, 2);
    }

    public static function toRwf(mixed $usd, mixed $rate): int
    {
        return (int) round(((float) $usd) * (float) $rate);
    }

    public static function formatRwf(mixed $amount): string
    {
        return number_format((float) $amount, 0).' RWF';
    }

    public static function formatBoth(mixed $usd, mixed $rate): string
    {
        return self::formatMoney($usd).' ('.self::formatRwf(self::toRwf($usd, $rate)).')';
    }

    public static function invoiceNumber(CarbonInterface $start): string
    {
        $sequence = ($start->year - 2026) + 1;

        return sprintf('IREME/gmasters/PH%03d/%d', max(1, $sequence), $start->year);
    }

    public static function invoiceDate(?CarbonInterface $date): string
    {
        if ($date === null) {
            return '';
        }

        $months = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sept', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];

        return $date->day.'/'.$months[(int) $date->month].'/'.$date->year;
    }

    public function prepare(?CarbonInterface $today = null): void
    {
        if (! Schema::hasTable('hosting_invoices') || ! Schema::hasTable('hosting_rates')) {
            return;
        }

        $this->rates();
        $today = $this->asDay($today);
        $first = Carbon::parse(config('hosting.prepaid_period_start', '2026-10-01'))->startOfDay();

        if ($today->lt($first)) {
            return;
        }

        $cursor = $first->copy();
        while ($cursor->lte($today)) {
            $this->ensurePeriod($cursor);
            $cursor->addYear();
        }
    }

    public function sync(?CarbonInterface $today = null): void
    {
        $this->prepare($today);
        $this->notify($today);
    }

    /**
     * Web requests try the reminder once per Kigali day. The daily command calls notify() directly.
     */
    public function remind(?CarbonInterface $today = null): void
    {
        if (! Schema::hasTable('hosting_invoices') || ! SiteNotificationMail::resendConfigured()) {
            return;
        }

        $day = $this->asDay($today)->toDateString();
        $key = 'hosting-reminder-'.$day;

        if (Cache::has($key)) {
            return;
        }

        $this->notify($today);
        Cache::put($key, true, now()->addHours(26));
    }

    public function notify(?CarbonInterface $today = null): void
    {
        if (! Schema::hasTable('hosting_invoices')) {
            return;
        }

        $today = $this->asDay($today);
        $coverage = $this->latestPaid();

        if (! $coverage) {
            $latest = HostingInvoice::query()->orderByDesc('period_end')->first();
            if ($latest && $latest->notice_expired_at === null) {
                $this->sendAndStamp($latest, $latest, 'expired', -1);
            }

            return;
        }

        $days = self::daysUntil($today, $coverage->period_end);
        $stage = self::reminderStage($days, [
            '30' => $coverage->notice_30_at !== null,
            '2' => $coverage->notice_2_at !== null,
            '0' => $coverage->notice_due_at !== null,
            'expired' => $coverage->notice_expired_at !== null,
        ]);

        if ($stage === null) {
            return;
        }

        $due = HostingInvoice::query()
            ->where('status', 'unpaid')
            ->whereDate('period_start', '>', $coverage->period_start->toDateString())
            ->orderBy('period_start')
            ->first();

        $this->sendAndStamp($coverage, $due, $stage, $days);
    }

    /**
     * @return array{level: string, title: string, message: string}|null
     */
    public function alert(?CarbonInterface $today = null): ?array
    {
        $this->prepare($today);

        if (! Schema::hasTable('hosting_invoices')) {
            return null;
        }

        $today = $this->asDay($today);
        $coverage = $this->latestPaid();

        if (! $coverage) {
            $latest = HostingInvoice::query()->orderByDesc('period_end')->first();
            if (! $latest) {
                return null;
            }

            return [
                'level' => 'danger',
                'title' => 'Hosting has expired',
                'message' => 'The hosting invoice '.$latest->number.' is waiting for payment. Open Hosting to download it and arrange the transfer.',
            ];
        }

        $days = self::daysUntil($today, $coverage->period_end);
        $ends = $coverage->period_end->format('j M Y');

        if ($days < 0) {
            return [
                'level' => 'danger',
                'title' => 'Hosting has expired',
                'message' => 'Coverage ended on '.$ends.'. Open Hosting, pay the new invoice, and a super admin can mark it paid.',
            ];
        }

        if ($days === 0) {
            return [
                'level' => 'danger',
                'title' => 'Hosting expires today',
                'message' => 'This hosting year ends today, '.$ends.'. Pay the renewal so the site stays online.',
            ];
        }

        if ($days <= 30) {
            $title = $days === 1 ? 'Hosting expires in 1 day' : 'Hosting expires in '.$days.' days';

            return [
                'level' => $days <= 2 ? 'danger' : 'warning',
                'title' => $title,
                'message' => 'Coverage runs until '.$ends.'. The renewal invoice is issued on 1 October. Payment details are on the Hosting page.',
            ];
        }

        return null;
    }

    public function rates(): HostingRate
    {
        return HostingRate::query()->firstOrCreate([], [
            'domain_amount' => 0,
            'ssl_amount' => 0,
            'support_amount' => 0,
            'usd_to_rwf' => config('hosting.usd_to_rwf', 1530),
        ]);
    }

    public function updateExchangeRate(mixed $usdToRwf): HostingRate
    {
        $rates = $this->rates();
        $rates->usd_to_rwf = $usdToRwf;
        $rates->save();

        return $rates;
    }

    /**
     * @param  array{domain_amount: float|int|string, ssl_amount: float|int|string, support_amount: float|int|string}  $amounts
     */
    public function updateRates(array $amounts): HostingRate
    {
        $rates = $this->rates();
        $rates->fill([
            'domain_amount' => $amounts['domain_amount'],
            'ssl_amount' => $amounts['ssl_amount'],
            'support_amount' => $amounts['support_amount'],
        ])->save();

        return $rates;
    }

    /**
     * @param  array{domain_amount: float|int|string, hosting_amount: float|int|string, ssl_amount: float|int|string, support_amount: float|int|string}  $amounts
     */
    public function updateInvoiceLines(HostingInvoice $invoice, array $amounts): void
    {
        $items = [
            ['key' => 'domain', 'label' => 'Domain registration', 'amount' => $this->money($amounts['domain_amount'])],
            ['key' => 'hosting', 'label' => 'Hosting', 'amount' => $this->money($amounts['hosting_amount'])],
            ['key' => 'ssl', 'label' => 'SSL', 'amount' => $this->money($amounts['ssl_amount'])],
            ['key' => 'support', 'label' => 'Support', 'amount' => $this->money($amounts['support_amount'])],
        ];

        $invoice->forceFill([
            'lines' => $items,
            'total' => $this->sumLines($items),
        ])->save();
    }

    public function deleteInvoice(HostingInvoice $invoice): void
    {
        $rates = $this->rates();
        $skipped = $this->skippedPeriods();
        $day = $invoice->period_start?->toDateString();
        if ($day) {
            $skipped[] = $day;
            $rates->skipped_periods = array_values(array_unique($skipped));
            $rates->save();
        }

        $invoice->delete();
    }

    public function lineAmount(HostingInvoice $invoice, string $key, mixed $fallback = 0): string
    {
        foreach ($invoice->lines ?? [] as $line) {
            if (($line['key'] ?? '') === $key) {
                return $this->money($line['amount'] ?? 0);
            }
        }

        return $this->money($fallback);
    }

    public function markPaid(HostingInvoice $invoice): void
    {
        if ($invoice->isPaid()) {
            return;
        }

        $invoice->forceFill([
            'status' => 'paid',
            'paid_at' => now(),
        ])->save();
    }

    public function sendManualReminder(HostingInvoice $invoice): bool
    {
        if ($invoice->isPaid()) {
            return false;
        }

        $sent = SiteNotificationMail::sendToTeam(new HostingRenewalMail(
            $invoice,
            $invoice,
            'manual',
            self::daysUntil(self::calendarToday(), $invoice->period_end),
        ));

        if ($sent) {
            $invoice->forceFill(['last_manual_reminder_at' => now()])->save();
        }

        return $sent;
    }

    /**
     * @return list<array{text: string, size?: int}>
     */
    public function pdfLines(HostingInvoice $invoice): array
    {
        $lines = [
            ['text' => (string) config('hosting.issuer'), 'size' => 16],
            ['text' => 'Hosting invoice '.$invoice->number, 'size' => 13],
            ['text' => ''],
            ['text' => 'Bill to: '.config('hosting.client')],
            ['text' => 'Issued: '.$invoice->issued_on?->format('j M Y')],
            ['text' => 'Period: '.$invoice->period_start?->format('j M Y').' to '.$invoice->period_end?->format('j M Y')],
            ['text' => 'Status: '.($invoice->isPaid() ? 'PAID' : 'UNPAID')],
            ['text' => ''],
            ['text' => 'Services'],
        ];

        foreach ($invoice->lines ?? [] as $line) {
            $amount = self::formatMoney($line['amount'] ?? 0);
            $note = (float) ($line['amount'] ?? 0) <= 0 ? ' (included)' : '';
            $lines[] = ['text' => ($line['label'] ?? 'Service').'  '.$amount.$note];
        }

        $lines[] = ['text' => ''];
        $lines[] = ['text' => 'Total  '.self::formatMoney($invoice->total), 'size' => 13];
        $lines[] = ['text' => ''];
        $lines[] = ['text' => 'Payment methods', 'size' => 13];

        foreach (config('hosting.payment_methods', []) as $method) {
            $lines[] = ['text' => (string) ($method['name'] ?? '')];
            foreach ($method['lines'] ?? [] as $detail) {
                $lines[] = ['text' => '  '.$detail];
            }
        }

        $lines[] = ['text' => ''];
        $lines[] = ['text' => 'Hosting is $80 per year. Domain, SSL, and support stay included until a super admin sets a cost for a future invoice.'];

        return $lines;
    }

    public function pdf(HostingInvoice $invoice): string
    {
        return PlainPdf::render($this->pdfLines($invoice));
    }

    public function nextTotal(): string
    {
        $rates = $this->rates();

        return self::formatBoth($this->sumLines($this->lineItems($rates)), $rates->usd_to_rwf);
    }

    private function ensurePeriod(CarbonInterface $periodStart): void
    {
        $start = Carbon::parse($periodStart->toDateString())->startOfDay();

        if (in_array($start->toDateString(), $this->skippedPeriods(), true)) {
            return;
        }

        if (HostingInvoice::query()->whereDate('period_start', $start->toDateString())->exists()) {
            return;
        }

        $prepaid = $start->toDateString() === Carbon::parse(config('hosting.prepaid_period_start', '2026-10-01'))->toDateString();
        $items = $this->lineItems($this->rates());
        $end = $start->copy()->addYear()->subDay();

        HostingInvoice::query()->create([
            'number' => self::invoiceNumber($start),
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'issued_on' => $start->toDateString(),
            'status' => $prepaid ? 'paid' : 'unpaid',
            'total' => $this->sumLines($items),
            'usd_to_rwf' => $this->rates()->usd_to_rwf ?: config('hosting.usd_to_rwf', 1530),
            'lines' => $items,
            'paid_at' => $prepaid ? $start->copy()->setTime(8, 0) : null,
        ]);
    }

    /**
     * @return list<array{key: string, label: string, amount: string}>
     */
    private function lineItems(HostingRate $rate): array
    {
        return [
            ['key' => 'domain', 'label' => 'Domain registration', 'amount' => $this->money($rate->domain_amount)],
            ['key' => 'hosting', 'label' => 'Hosting', 'amount' => $this->money(config('hosting.hosting_amount', 80))],
            ['key' => 'ssl', 'label' => 'SSL', 'amount' => $this->money($rate->ssl_amount)],
            ['key' => 'support', 'label' => 'Support', 'amount' => $this->money($rate->support_amount)],
        ];
    }

    /**
     * @param  list<array{amount: string}>  $items
     */
    private function sumLines(array $items): string
    {
        $total = 0.0;
        foreach ($items as $item) {
            $total += (float) $item['amount'];
        }

        return $this->money($total);
    }

    private function money(mixed $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /**
     * @return list<string>
     */
    private function skippedPeriods(): array
    {
        $skipped = $this->rates()->skipped_periods ?? [];

        return is_array($skipped) ? array_values($skipped) : [];
    }

    private function latestPaid(): ?HostingInvoice
    {
        return HostingInvoice::query()->where('status', 'paid')->orderByDesc('period_end')->first();
    }

    private function sendAndStamp(HostingInvoice $coverage, ?HostingInvoice $due, string $stage, int $days): void
    {
        $sent = SiteNotificationMail::sendToTeam(new HostingRenewalMail($coverage, $due, $stage, $days));

        if (! $sent) {
            return;
        }

        $column = match ($stage) {
            'month' => 'notice_30_at',
            'soon' => 'notice_2_at',
            'today' => 'notice_due_at',
            default => 'notice_expired_at',
        };

        $coverage->forceFill([$column => now()])->save();
    }

    private function asDay(?CarbonInterface $today): Carbon
    {
        if ($today === null) {
            return self::calendarToday();
        }

        return Carbon::parse($today->toDateString())->startOfDay();
    }
}
