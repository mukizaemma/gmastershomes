<?php

namespace App\Mail;

use App\Models\HostingInvoice;
use App\Services\HostingBilling;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HostingRenewalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public HostingInvoice $coverage,
        public ?HostingInvoice $due,
        public string $stage,
        public int $days,
    ) {
    }

    public function build(): self
    {
        return $this->subject($this->subjectLine())
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.hosting-renewal', [
                'amount' => $this->due
                    ? HostingBilling::formatBoth($this->due->total, $this->due->exchangeRate())
                    : app(HostingBilling::class)->nextTotal(),
                'paymentMethods' => config('hosting.payment_methods', []),
            ]);
    }

    private function subjectLine(): string
    {
        $name = (string) config('hosting.client', 'GMasters Homes');

        return match ($this->stage) {
            'manual' => 'Payment reminder — '.($this->due?->number ?: $this->coverage->number),
            'expired' => 'Hosting has expired — '.$name,
            'today' => 'Hosting expires today — '.$name,
            default => 'Hosting expires in '.$this->days.' '.($this->days === 1 ? 'day' : 'days').' — '.$name,
        };
    }
}
