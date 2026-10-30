<div class="hosting-tools d-flex flex-wrap gap-2 mb-4">
    <a class="btn btn-primary" href="{{ route('content-management.hosting.download', $invoice) }}">Download invoice</a>
    <button type="button" class="btn btn-outline-dark" onclick="window.print()">Print / Save as PDF</button>
    @unless(request()->routeIs('content-management.hosting.show'))
        <a class="btn btn-outline-secondary" href="{{ route('content-management.hosting.show', $invoice) }}">Open invoice</a>
    @endunless
    @if(! $invoice->isPaid() && auth()->user()->isSuperAdmin())
        <form method="post" action="{{ route('content-management.hosting.paid', $invoice) }}" onsubmit="return confirm('Mark {{ $invoice->number }} as paid?');">
            @csrf
            <button type="submit" class="btn btn-success">Mark as paid</button>
        </form>
        <form method="post" action="{{ route('content-management.hosting.remind', $invoice) }}" onsubmit="return confirm('Send a payment reminder for {{ $invoice->number }}?');">
            @csrf
            <button type="submit" class="btn btn-warning text-dark">Send payment reminder</button>
        </form>
    @endif
</div>
@if($invoice->last_manual_reminder_at)
    <p class="hosting-tools small text-muted">Last manual reminder sent {{ $invoice->last_manual_reminder_at->timezone(config('hosting.timezone'))->format('j M Y H:i') }}.</p>
@endif
