<?php

namespace App\Exceptions;

use App\Models\Booking;
use Exception;

class RecentRequestExists extends Exception
{
    public function __construct(public Booking $booking)
    {
        parent::__construct('A request from this email was submitted in the last 7 days.');
    }

    /**
     * @return array{id: int, email: string, created_on: string, kind: string}
     */
    public function payload(): array
    {
        return [
            'id' => (int) $this->booking->id,
            'email' => (string) $this->booking->email,
            'created_on' => optional($this->booking->created_at)->format('j M Y') ?: '',
            'kind' => $this->booking->reservation_type === 'tour_activity' ? 'tour' : 'room',
        ];
    }
};
