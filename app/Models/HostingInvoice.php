<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingInvoice extends Model
{
    protected $fillable = [
        'number',
        'period_start',
        'period_end',
        'issued_on',
        'status',
        'total',
        'usd_to_rwf',
        'lines',
        'paid_at',
        'last_manual_reminder_at',
        'notice_30_at',
        'notice_2_at',
        'notice_due_at',
        'notice_expired_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'issued_on' => 'date',
        'total' => 'decimal:2',
        'usd_to_rwf' => 'decimal:2',
        'lines' => 'array',
        'paid_at' => 'datetime',
        'last_manual_reminder_at' => 'datetime',
        'notice_30_at' => 'datetime',
        'notice_2_at' => 'datetime',
        'notice_due_at' => 'datetime',
        'notice_expired_at' => 'datetime',
    ];

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function exchangeRate(): float
    {
        $rate = (float) $this->usd_to_rwf;

        return $rate > 0 ? $rate : (float) config('hosting.usd_to_rwf', 1530);
    }

    public function downloadFilename(): string
    {
        $start = $this->period_start?->format('Y') ?? 'invoice';
        $end = $this->period_end?->format('Y') ?? '';

        $safe = str_replace('/', '-', $this->number ?: 'invoice');

        return $safe.'.html';
    }
}
