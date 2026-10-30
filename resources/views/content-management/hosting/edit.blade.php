@extends('layouts.adminBase')

@section('content')
<div class="admin-livewire-page d-flex w-100 align-items-stretch">
@include('content-management.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')
    <div class="container-fluid pt-4 px-4">
        <p><a href="{{ route('content-management.hosting.index') }}">Back to invoices</a></p>
        <div class="bg-white rounded shadow-sm border p-4 mb-4">
            <h4 class="mb-1">Edit {{ $invoice->number }}</h4>
            <p class="text-muted">{{ $invoice->period_start?->format('j M Y') }} – {{ $invoice->period_end?->format('j M Y') }}. Amounts are in US dollars. Francs on the invoice use this invoice’s rate of 1 USD = {{ number_format($invoice->exchangeRate(), 0) }} RWF.</p>
            <form method="post" action="{{ route('content-management.hosting.update', $invoice) }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label" for="domain_amount">Domain registration</label>
                    <input class="form-control" id="domain_amount" name="domain_amount" type="number" min="0" step="0.01" value="{{ old('domain_amount', $billing->lineAmount($invoice, 'domain')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="hosting_amount">Hosting</label>
                    <input class="form-control" id="hosting_amount" name="hosting_amount" type="number" min="0" step="0.01" value="{{ old('hosting_amount', $billing->lineAmount($invoice, 'hosting', config('hosting.hosting_amount'))) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="ssl_amount">SSL</label>
                    <input class="form-control" id="ssl_amount" name="ssl_amount" type="number" min="0" step="0.01" value="{{ old('ssl_amount', $billing->lineAmount($invoice, 'ssl')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="support_amount">Support</label>
                    <input class="form-control" id="support_amount" name="support_amount" type="number" min="0" step="0.01" value="{{ old('support_amount', $billing->lineAmount($invoice, 'support')) }}" required>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save invoice</button>
                    <a class="btn btn-outline-secondary" href="{{ route('content-management.hosting.show', $invoice) }}">Cancel</a>
                </div>
            </form>
            @if($errors->any())
                <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
