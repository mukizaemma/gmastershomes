@extends('layouts.adminBase')

@section('content')
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
        <p><a href="{{ route('content-management.hosting.index') }}">Back to invoices</a></p>
        <div class="bg-white rounded shadow-sm border p-4 mb-4">
            <h4 class="mb-2">Hosting settings</h4>
            <p class="text-muted">These amounts apply to the next invoice. Invoices already issued keep the figures printed on them.</p>

            <h5 class="mb-2">Exchange rate</h5>
            <p class="mb-3">1 USD = {{ number_format((float) $rates->usd_to_rwf, 0) }} RWF.</p>
            @if(auth()->user()->canEditHostingRate())
                <form method="post" action="{{ route('content-management.hosting.exchange-rate') }}" class="row g-3 mb-4">
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
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            @else
                <p class="text-muted">The exchange rate is updated from the Ireme super admin account.</p>
            @endif

            <h5 class="mb-2">Service amounts for the next invoice</h5>
            <p class="text-muted">Hosting stays at {{ \App\Services\HostingBilling::formatMoney(config('hosting.hosting_amount')) }}. Domain registration, SSL, and support stay at {{ \App\Services\HostingBilling::formatMoney(0) }} until a cost is entered here.</p>
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
        </div>
    </div>
</div>
</div>
@endsection
