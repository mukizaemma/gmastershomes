<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\BookingSubmissionService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingUpdatedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function build(): self
    {
        $mailable = $this->subject('Request updated — '.$this->booking->names.' ('.$this->booking->id.')')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.booking-updated-admin', [
                'hotelName' => BookingSubmissionService::hotelName(),
            ]);

        if (filled($this->booking->email)) {
            $mailable->replyTo($this->booking->email, $this->booking->names ?: $this->booking->email);
        }

        return $mailable;
    }
}
