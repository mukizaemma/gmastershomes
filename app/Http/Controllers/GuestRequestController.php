<?php

namespace App\Http\Controllers;

use App\Exceptions\RecentRequestExists;
use App\Models\Booking;
use App\Models\Room;
use App\Models\TourActivity;
use App\Services\GuestRequestService;
use App\Support\BookingStayReasons;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuestRequestController extends Controller
{
    public function show(string $token, GuestRequestService $requests)
    {
        $booking = $requests->findByToken($token);

        return view('frontend.guest-request', $this->pageData($booking, $requests));
    }

    public function update(Request $request, string $token, GuestRequestService $requests)
    {
        $booking = $requests->findByToken($token);
        $requests->update($booking, $request);

        return redirect()
            ->route('guest-request.manage', ['token' => $token])
            ->with('success', site_trans('booking.guest_saved'));
    }

    public function cancel(string $token, GuestRequestService $requests)
    {
        $booking = $requests->findByToken($token);

        return view('frontend.guest-request-cancel', [
            'booking' => $booking,
            'editable' => $requests->canChange($booking),
        ]);
    }

    public function reviewCancel(Request $request, string $token, GuestRequestService $requests)
    {
        $booking = $requests->findByToken($token);
        if (! $requests->canChange($booking)) {
            return redirect()->route('guest-request.cancel', ['token' => $token]);
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $request->session()->put($this->reasonKey($token), trim($validated['reason']));

        return redirect()->route('guest-request.cancel-confirm', ['token' => $token]);
    }

    public function confirmCancel(string $token, GuestRequestService $requests)
    {
        $booking = $requests->findByToken($token);
        $reason = (string) session($this->reasonKey($token), '');
        if ($reason === '') {
            return redirect()->route('guest-request.cancel', ['token' => $token]);
        }

        return view('frontend.guest-request-cancel-confirm', [
            'booking' => $booking,
            'reason' => $reason,
            'editable' => $requests->canChange($booking),
        ]);
    }

    public function storeCancel(Request $request, string $token, GuestRequestService $requests)
    {
        $booking = $requests->findByToken($token);
        $reason = (string) session($this->reasonKey($token), '');
        if ($reason === '') {
            return redirect()->route('guest-request.cancel', ['token' => $token]);
        }

        $request->validate([
            'confirm_cancel' => 'accepted',
        ]);

        try {
            $requests->cancel($booking, $reason);
        } catch (ValidationException $e) {
            return redirect()
                ->route('guest-request.cancel', ['token' => $token])
                ->withErrors($e->errors());
        }

        $request->session()->forget($this->reasonKey($token));

        return redirect()
            ->route('guest-request.manage', ['token' => $token])
            ->with('success', site_trans('booking.guest_cancelled'));
    }

    public function sendAccessLink(Request $request, GuestRequestService $requests)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'booking_id' => 'required|integer',
        ]);

        $booking = Booking::query()->find($validated['booking_id']);
        $email = strtolower(trim($validated['email']));
        $matches = $booking
            && strtolower((string) $booking->email) === $email
            && in_array($booking->reservation_type, ['room', 'tour_activity'], true)
            && $booking->status !== 'cancelled'
            && $booking->created_at
            && $booking->created_at->gte(now()->subDays(7));

        $payload = $booking ? (new RecentRequestExists($booking))->payload() : null;

        if (! $matches) {
            return redirect()->back()->with('recent_request_link_failed', true);
        }

        $sent = $requests->sendAccessLink($booking);

        return redirect()->back()->with('recent_request', $payload)->with(
            $sent ? 'recent_request_link_sent' : 'recent_request_link_failed',
            true
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function pageData(Booking $booking, GuestRequestService $requests): array
    {
        return [
            'booking' => $booking,
            'editable' => $requests->canChange($booking),
            'rooms' => Room::query()->where('status', 'Active')->orderBy('title')->get(),
            'activities' => TourActivity::query()->active()->ordered()->get(['id', 'title']),
            'stayReasons' => BookingStayReasons::options(),
            'selectedActivityIds' => collect($booking->selected_tours ?? [])
                ->map(fn ($item) => (int) (is_array($item) ? ($item['id'] ?? 0) : 0))
                ->filter()
                ->values()
                ->all(),
        ];
    }

    private function reasonKey(string $token): string
    {
        return 'guest_cancel_reason.'.$token;
    }
}
