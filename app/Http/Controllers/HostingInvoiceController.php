<?php

namespace App\Http\Controllers;

use App\Models\HostingInvoice;
use App\Services\HostingBilling;
use Illuminate\Http\Request;

class HostingInvoiceController extends Controller
{
    public function index(HostingBilling $billing)
    {
        $billing->prepare();
        $billing->remind();

        $invoices = HostingInvoice::query()->orderByDesc('period_start')->get();
        $rates = $billing->rates();
        $today = HostingBilling::calendarToday();
        $currentStart = HostingBilling::periodStart($today)->toDateString();
        $alert = $billing->alert();

        return view('content-management.hosting.index', compact('invoices', 'rates', 'currentStart', 'alert'));
    }

    public function show(HostingInvoice $invoice)
    {
        return view('content-management.hosting.show', compact('invoice'));
    }

    public function download(HostingInvoice $invoice)
    {
        $html = view('content-management.hosting.download', compact('invoice'))->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$invoice->downloadFilename().'"',
        ]);
    }

    public function updateExchangeRate(Request $request, HostingBilling $billing)
    {
        abort_unless($request->user()?->canEditHostingRate(), 403);

        $data = $request->validate([
            'usd_to_rwf' => ['required', 'numeric', 'min:1', 'max:1000000'],
        ]);

        $billing->updateExchangeRate($data['usd_to_rwf']);

        return redirect()
            ->route('content-management.hosting.index')
            ->with('success', 'The exchange rate for future invoices is now 1 USD = '.number_format((float) $data['usd_to_rwf'], 0).' RWF. Invoices already issued are unchanged.');
    }

    public function remind(Request $request, HostingInvoice $invoice, HostingBilling $billing)
    {
        abort_unless($request->user()?->isSuperAdmin(), 403);

        if ($invoice->isPaid()) {
            return redirect()
                ->route('content-management.hosting.show', $invoice)
                ->with('error', $invoice->number.' is already paid.');
        }

        $sent = $billing->sendManualReminder($invoice);

        return redirect()
            ->route('content-management.hosting.show', $invoice)
            ->with($sent ? 'success' : 'error', $sent
                ? 'Payment reminder sent for '.$invoice->number.'.'
                : 'The reminder was not sent. Check that email delivery is configured.');
    }

    public function updateRates(Request $request, HostingBilling $billing)
    {
        abort_unless($request->user()?->isSuperAdmin(), 403);

        $data = $request->validate([
            'domain_amount' => ['required', 'numeric', 'min:0', 'max:99999'],
            'ssl_amount' => ['required', 'numeric', 'min:0', 'max:99999'],
            'support_amount' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);

        $billing->updateRates($data);

        return redirect()
            ->route('content-management.hosting.index')
            ->with('success', 'Future hosting invoices will use these service amounts. The invoice already issued is unchanged.');
    }

    public function markPaid(Request $request, HostingInvoice $invoice, HostingBilling $billing)
    {
        abort_unless($request->user()?->isSuperAdmin(), 403);

        $billing->markPaid($invoice);

        return redirect()
            ->route('content-management.hosting.index')
            ->with('success', $invoice->number.' is marked paid.');
    }
}
