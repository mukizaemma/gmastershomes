@extends('layouts.adminBase')

@section('content')
@include('content-management.hosting.styles')
<style>
    @media print {
        .sidebar, .navbar, .hosting-tools, .alert, .content > .container-fluid > .bg-white { display: none !important; }
        .pay-request { display: block !important; border: 0; }
    }
</style>
<div class="admin-livewire-page d-flex w-100 align-items-stretch">
@include('content-management.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(! empty($alert))
            <div class="alert alert-{{ $alert['level'] === 'danger' ? 'danger' : 'warning' }}" role="alert">
                <strong>{{ $alert['title'] }}.</strong> {{ $alert['message'] }}
            </div>
        @endif

        @php $current = $invoices->first(fn ($invoice) => $invoice->period_start?->toDateString() === $currentStart); @endphp
        @if($current)
            @include('content-management.hosting.document', ['invoice' => $current])
            @include('content-management.hosting.actions', ['invoice' => $current])
        @endif

        <div class="bg-white rounded shadow-sm border p-4 mb-4 hosting-tools">
            <h5 class="mb-2">Invoices</h5>
            <p class="text-muted">Every hosting year stays here. Open or download the current invoice and any past invoice.</p>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Period</th>
                            <th>USD</th>
                            <th>RWF</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->number }}</td>
                                <td>{{ $invoice->period_start?->format('j M Y') }} – {{ $invoice->period_end?->format('j M Y') }}</td>
                                <td>{{ \App\Services\HostingBilling::formatMoney($invoice->total) }}</td>
                                <td>{{ \App\Services\HostingBilling::formatRwf(\App\Services\HostingBilling::toRwf($invoice->total, $invoice->exchangeRate())) }}</td>
                                <td>{{ $invoice->isPaid() ? 'Paid' : 'Unpaid' }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('content-management.hosting.show', $invoice) }}">View</a>
                                    ·
                                    <a href="{{ route('content-management.hosting.download', $invoice) }}">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">The first invoice appears here after the hosting records are created.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm border p-4 mb-4 hosting-tools">
            <h5 class="mb-2">Exchange rate</h5>
            <p class="mb-3">1 USD = {{ number_format((float) $rates->usd_to_rwf, 0) }} RWF. Invoices already issued keep their own rate.</p>
            @if(auth()->user()->canEditHostingRate())
                <form method="post" action="{{ route('content-management.hosting.exchange-rate') }}" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label" for="usd_to_rwf">RWF for 1 USD</label>
                        <input class="form-control" id="usd_to_rwf" name="usd_to_rwf" type="number" min="1" step="0.01" value="{{ old('usd_to_rwf', $rates->usd_to_rwf) }}" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark">Update exchange rate</button>
                    </div>
                </form>
                @error('usd_to_rwf')
                    <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
                @enderror
            @else
                <p class="text-muted mb-0">The exchange rate is updated by the Ireme super admin account.</p>
            @endif
        </div>

        <div class="bg-white rounded shadow-sm border p-4 mb-4 hosting-tools">
            <h5 class="mb-2">Service amounts for the next invoice</h5>
            <p class="text-muted">Hosting stays at {{ \App\Services\HostingBilling::formatMoney(config('hosting.hosting_amount')) }}. Domain registration, SSL, and support stay at {{ \App\Services\HostingBilling::formatMoney(0) }} until a super admin enters a cost. Saving here changes invoices that have not been created yet.</p>
            @if(auth()->user()->isSuperAdmin())
                <form method="post" action="{{ route('content-management.hosting.rates') }}" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label" for="domain_amount">Domain registration (USD)</label>
                        <input class="form-control" id="domain_amount" name="domain_amount" type="number" min="0" step="0.01" value="{{ old('domain_amount', $rates->domain_amount) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hosting_amount">Hosting (USD)</label>
                        <input class="form-control" id="hosting_amount" type="text" value="{{ number_format((float) config('hosting.hosting_amount'), 2, '.', '') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="ssl_amount">SSL (USD)</label>
                        <input class="form-control" id="ssl_amount" name="ssl_amount" type="number" min="0" step="0.01" value="{{ old('ssl_amount', $rates->ssl_amount) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="support_amount">Support (USD)</label>
                        <input class="form-control" id="support_amount" name="support_amount" type="number" min="0" step="0.01" value="{{ old('support_amount', $rates->support_amount) }}" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark">Save amounts for future invoices</button>
                    </div>
                </form>
                @if($errors->any() && ! $errors->has('usd_to_rwf'))
                    <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
                @endif
            @else
                <ul class="mb-0">
                    <li>Domain registration: {{ \App\Services\HostingBilling::formatMoney($rates->domain_amount) }}</li>
                    <li>Hosting: {{ \App\Services\HostingBilling::formatMoney(config('hosting.hosting_amount')) }}</li>
                    <li>SSL: {{ \App\Services\HostingBilling::formatMoney($rates->ssl_amount) }}</li>
                    <li>Support: {{ \App\Services\HostingBilling::formatMoney($rates->support_amount) }}</li>
                </ul>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
