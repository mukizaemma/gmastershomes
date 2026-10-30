<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingRate extends Model
{
    protected $fillable = [
        'domain_amount',
        'ssl_amount',
        'support_amount',
        'usd_to_rwf',
        'skipped_periods',
    ];

    protected $casts = [
        'domain_amount' => 'decimal:2',
        'ssl_amount' => 'decimal:2',
        'support_amount' => 'decimal:2',
        'usd_to_rwf' => 'decimal:2',
        'skipped_periods' => 'array',
    ];
}
