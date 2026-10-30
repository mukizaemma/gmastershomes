<?php

namespace App\Services;

use App\Mail\BookingAccessLinkMail;
use App\Mail\BookingCancelledAdminMail;
use App\Mail\BookingCancelledGuestMail;
use App\Mail\BookingUpdatedAdminMail;
use App\Mail\BookingUpdatedGuestMail;
use App\Models\Booking;
use App\Models\Room;
use App\Models\TourActivity;
use App\Rules\ActiveEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GuestRequestService
{
    public function recentForEmail(?string $email): ?Booking
    {
        $email = strtolower(trim((string) $email));
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return Booking::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->whereIn('reservation_type', ['room', 'tour_activity'])
            ->where('created_at', '>=', now()->subDays(7))
            ->where('status', '!=', 'cancelled')
            ->latest('id')
            ->first();
    }

    public function makeToken(): string
    {
        do {
            $token = Str::random(48);
        } while (Booking::query()->where('manage_token', $token)->exists());

        return $token;
    }

    public function ensureToken(Booking $booking): Booking
    {
        if (filled($booking->manage_token)) {
            return $booking;
        }

        $booking->manage_token = $this->makeToken();
        $booking->save();

        return $booking;
    }

    public function findByToken(string $token): Booking
    {
        return Booking::query()
            ->where('manage_token', $token)
            ->whereIn('reservation_type', ['room', 'tour_activity'])
            ->firstOrFail();
    }

    public function canChange(Booking $booking): bool
    {
        if (! in_array($booking->reservation_type, ['room', 'tour_activity'], true)) {
            return false;
        }

        if (in_array($booking->status, ['cancelled', 'No Show'], true)) {
            return false;
        }

        if ($booking->checked_in_at || $booking->checked_out_at) {
            return false;
        }

        if (! $booking->checkin_date) {
            return $booking->status === 'pending';
        }

        return $booking->checkin_date->copy()->startOfDay()->isFuture();
    }

    public function sendAccessLink(Booking $booking): bool
    {
        $booking = $this->ensureToken($booking);
        if (! filled($booking->email)) {
            return false;
        }

        return SiteNotificationMail::sendToGuest($booking->email, new BookingAccessLinkMail($booking));
    }

    public function update(Booking $booking, Request $request): Booking
    {
        if (! $this->canChange($booking)) {
            throw ValidationException::withMessages([
                'email' => 'This request can no longer be changed.',
            ]);
        }

        if ($booking->reservation_type === 'tour_activity') {
            return $this->updateTour($booking, $request);
        }

        return $this->updateRoom($booking, $request);
    }

    public function cancel(Booking $booking, string $reason): Booking
    {
        if (! $this->canChange($booking)) {
            throw ValidationException::withMessages([
                'reason' => 'This request can no longer be cancelled.',
            ]);
        }

        $booking->status = 'cancelled';
        $booking->cancellation_reason = trim($reason);
        $booking->save();
        $booking->load(['room', 'facility', 'tourActivity']);

        SiteNotificationMail::sendToTeam(new BookingCancelledAdminMail($booking));
        if (filled($booking->email)) {
            SiteNotificationMail::sendToGuest($booking->email, new BookingCancelledGuestMail($booking));
        }

        return $booking;
    }

    private function updateRoom(Booking $booking, Request $request): Booking
    {
        $validated = $request->validate([
            'names' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', new ActiveEmail],
            'phone' => 'required|string|max:60',
            'country' => 'nullable|string|max:100',
            'stay_reason' => 'nullable|string|max:50',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms' => 'nullable|integer|min:1',
            'room_id' => 'required|exists:rooms,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $adults = (int) $validated['adults'];
        $children = (int) ($validated['children'] ?? 0);
        $roomCount = max(1, (int) ($validated['rooms'] ?? 1));
        $extraBeds = 0;
        if (preg_match('/Extra beds requested:\s*(\d+)/', (string) $booking->message, $matches)) {
            $extraBeds = (int) $matches[1];
        }

        app(RoomAvailabilityService::class)->assertRequestFits(
            $room,
            $validated['checkin'],
            $validated['checkout'],
            $roomCount,
            $adults,
            $children,
            (int) $booking->id
        );

        $quote = app(RoomDiscountService::class)->quote(
            $room,
            $validated['checkin'],
            $validated['checkout'],
            $adults,
            $children,
            $roomCount,
            $extraBeds,
            $booking->board,
            $booking->occupancy,
            $booking->currency
        );

        $booking->fill([
            'names' => $validated['names'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'] ?? null,
            'stay_reason' => $validated['stay_reason'] ?? null,
            'checkin_date' => $validated['checkin'],
            'checkout_date' => $validated['checkout'],
            'adults' => $adults,
            'children' => $children,
            'rooms' => $roomCount,
            'room_id' => $room->id,
            'message' => $validated['message'] ?? null,
            'total_amount' => (int) round($quote['total']),
            'list_amount' => (int) round($quote['list_total']),
            'discount_amount' => (int) round($quote['discount_total']),
            'discount_label' => $quote['label'] !== '' ? $quote['label'] : null,
            'balance_amount' => max(0, (int) round($quote['total']) - (int) $booking->paid_amount),
        ]);
        $booking->save();

        return $this->notifyUpdated($booking);
    }

    private function updateTour(Booking $booking, Request $request): Booking
    {
        $validated = $request->validate([
            'names' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', new ActiveEmail],
            'phone' => 'required|string|max:60',
            'activity_start' => 'required|date|after_or_equal:today',
            'activity_end' => 'required|date|after_or_equal:activity_start',
            'guests' => 'required|integer|min:1|max:40',
            'accommodation_preference' => 'required|string|max:255',
            'transport_preference' => 'required|string|max:255',
            'destinations' => 'required|string|max:2000',
            'activity_ids' => 'required|array|min:1|max:20',
            'activity_ids.*' => 'integer|exists:tour_activities,id',
            'notes' => 'nullable|string|max:5000',
        ]);

        $activities = TourActivity::query()
            ->whereIn('id', $validated['activity_ids'])
            ->ordered()
            ->get(['id', 'title']);

        $booking->fill([
            'names' => $validated['names'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'checkin_date' => $validated['activity_start'],
            'checkout_date' => $validated['activity_end'],
            'adults' => (int) $validated['guests'],
            'tour_activity_id' => $activities->first()?->id,
            'accommodation_preference' => $validated['accommodation_preference'],
            'transport_preference' => $validated['transport_preference'],
            'destinations' => $validated['destinations'],
            'selected_tours' => $activities->map(fn (TourActivity $activity) => [
                'id' => $activity->id,
                'title' => $activity->title,
            ])->values()->all(),
            'message' => $validated['notes'] ?? null,
            'total_amount' => 0,
            'balance_amount' => 0,
        ]);
        $booking->save();

        return $this->notifyUpdated($booking);
    }

    private function notifyUpdated(Booking $booking): Booking
    {
        $booking->load(['room', 'facility', 'tourActivity']);
        SiteNotificationMail::sendToTeam(new BookingUpdatedAdminMail($booking));
        if (filled($booking->email)) {
            SiteNotificationMail::sendToGuest($booking->email, new BookingUpdatedGuestMail($booking));
        }

        return $booking;
    }
};
