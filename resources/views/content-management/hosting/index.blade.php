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

        <div class="bg-white rounded shadow-sm border p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h4 class="mb-0">Hosting invoices</h4>
                @if(auth()->user()->isSuperAdmin())
                    <a class="btn btn-dark" href="{{ route('content-management.hosting.settings') }}">Settings</a>
                @endif
            </div>
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
                                    @if(auth()->user()->isSuperAdmin())
                                        · <a href="{{ route('content-management.hosting.edit', $invoice) }}">Edit</a>
                                        ·
                                        <form method="post" action="{{ route('content-management.hosting.destroy', $invoice) }}" class="d-inline" onsubmit="return confirm('Delete {{ $invoice->number }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 align-baseline">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No hosting invoices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
