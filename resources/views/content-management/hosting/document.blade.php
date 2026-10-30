@php
    $logoFile = public_path('assets/images/ireme-logo.png');
    $stampFile = public_path('assets/images/ireme-stamp.png');
    $logoSrc = is_file($logoFile) ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($logoFile)) : '';
    $stampSrc = is_file($stampFile) ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($stampFile)) : '';
    $rate = $invoice->exchangeRate();
    $website = (string) config('hosting.client_website');
    $websiteLabel = preg_replace('#^https?://#', '', $website);
    $handover = (string) config('hosting.handover_url');
    $handoverLabel = preg_replace('#^https?://#', '', $handover);
    $totalRwf = \App\Services\HostingBilling::toRwf($invoice->total, $rate);
    $period = $invoice->period_start?->format('j F Y').' to '.$invoice->period_end?->format('j F Y');
@endphp

<article class="pay-request" id="hosting-invoice">
    <header class="pay-request__head">
        @if($logoSrc !== '')
            <img class="pay-request__logo" src="{{ $logoSrc }}" alt="Ireme Technologies">
        @endif
        <div class="pay-request__issuer">
            <span class="pay-request__company">{{ config('hosting.issuer_legal') }}</span>
            @foreach(config('hosting.issuer_lines', []) as $line)
                <div>{{ $line }}</div>
            @endforeach
            <div><strong>Tel:</strong> {{ config('hosting.issuer_phone') }}</div>
            <div><strong>Email:</strong> {{ config('hosting.issuer_email') }}</div>
        </div>
    </header>
    <hr class="pay-request__rule">

    <h1>Payment Request</h1>
    <p class="pay-request__meta"><strong>Invoice No.:</strong> {{ $invoice->number }}</p>
    <p class="pay-request__meta"><strong>Invoicing</strong> {{ config('hosting.client') }}</p>
    <p class="pay-request__meta"><strong>Website:</strong> <a href="{{ $website }}">{{ $websiteLabel }}</a></p>
    <p class="pay-request__meta">Handover link: <a href="{{ $handover }}">{{ $handoverLabel }}</a></p>
    @if($invoice->isPaid())
        <p class="pay-request__meta"><strong>Status:</strong> Paid
            @if($invoice->paid_at)
                on {{ \App\Services\HostingBilling::invoiceDate($invoice->paid_at) }}
            @endif
        </p>
    @endif

    <p class="pay-request__note">
        <strong>Note:</strong>
        @if($invoice->isPaid())
            Ireme Technologies confirms receipt of <strong>{{ \App\Services\HostingBilling::formatRwf($totalRwf) }}</strong> ({{ \App\Services\HostingBilling::formatMoney($invoice->total) }}) for domain registration, hosting, SSL, and support covering {{ $period }}.
        @else
            Ireme Technologies kindly reminds you to settle the outstanding balance of <strong>{{ \App\Services\HostingBilling::formatRwf($totalRwf) }}</strong> ({{ \App\Services\HostingBilling::formatMoney($invoice->total) }}) for domain registration, hosting, SSL, and support covering {{ $period }}.
        @endif
    </p>
    <p>Please note that an EBM receipt will be issued once the purchase code has been shared.</p>
    <p class="pay-request__rate">Exchange rate on this invoice: <strong>1 USD = {{ number_format($rate, 0) }} RWF</strong></p>

    <h2>Service details</h2>
    <table class="pay-request__services">
        <thead>
            <tr>
                <th class="col-num">#</th>
                <th>Service Description</th>
                <th class="col-amt">Amount (USD)</th>
                <th class="col-amt">Amount (RWF)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->lines ?? [] as $index => $line)
                @php
                    $usd = (float) ($line['amount'] ?? 0);
                    $rwf = \App\Services\HostingBilling::toRwf($usd, $rate);
                @endphp
                <tr>
                    <td class="col-num">{{ $index + 1 }}</td>
                    <td>
                        {{ $line['label'] ?? 'Service' }}
                        @if($usd <= 0)
                            <span class="pay-request__included">Included</span>
                        @endif
                    </td>
                    <td class="col-amt">{{ \App\Services\HostingBilling::formatMoney($usd) }}</td>
                    <td class="col-amt">{{ number_format($rwf, 0) }}</td>
                </tr>
            @endforeach
            <tr class="pay-request__total">
                <td colspan="2">Amount to Pay (VAT inclusive)</td>
                <td class="col-amt">{{ \App\Services\HostingBilling::formatMoney($invoice->total) }}</td>
                <td class="col-amt">{{ number_format($totalRwf, 0) }}</td>
            </tr>
        </tbody>
    </table>

    <h2 class="pay-request__pay-title">2. Payment Method:</h2>
    <table class="pay-request__pay">
        <thead>
            <tr>
                <th>Bank Transfer</th>
                <th>MoMo Pay</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Account No:</strong> 4491274576, BPR</td>
                <td>20033</td>
            </tr>
            <tr>
                <td><strong>Names:</strong> Ireme Technologies</td>
                <td>Ireme Technologies</td>
            </tr>
        </tbody>
    </table>

    <div class="pay-request__sign">
        <div>
            <div>Prepared by,</div>
            <div class="pay-request__name">{{ config('hosting.prepared_by') }}</div>
            <div><strong>Date:</strong> {{ \App\Services\HostingBilling::invoiceDate($invoice->issued_on) }}</div>
        </div>
        @if($stampSrc !== '')
            <img class="pay-request__stamp" src="{{ $stampSrc }}" alt="Ireme Technologies stamp">
        @endif
    </div>
</article>
