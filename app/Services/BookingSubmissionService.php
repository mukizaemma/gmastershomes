<?php

namespace App\Services;

use App\Exceptions\RecentRequestExists;
use App\Mail\BookingSubmittedAdminMail;
use App\Mail\BookingSubmittedGuestMail;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Setting;
use App\Models\TourActivity;
use App\Rules\ActiveEmail;
use Illuminate\Http\Request;

class BookingSubmissionService
{
    /**
     * @return array{booking: Booking, admin_sent: bool, guest_sent: bool}
     */
    public function submit(Request $request): array
    {
        return $this->deliver($this->createBooking($request));
    }

    /**
     * @return array{booking: Booking, admin_sent: bool, guest_sent: bool}
     */
    public function submitTour(Request $request): array
    {
        $validated = $request->validate([
            'names' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', new ActiveEmail],
            'phone' => 'required|string|max:60',
            'activity_id' => 'nullable|integer|exists:tour_activities,id',
            'activity_start' => 'required|date|after_or_equal:today',
            'activity_end' => 'required|date|after_or_equal:activity_start',
            'activity_ids' => 'required|array|min:1|max:20',
            'activity_ids.*' => 'integer|exists:tour_activities,id',
            'guests' => 'required|integer|min:1|max:40',
            'accommodation_preference' => 'required|string|max:255',
            'transport_preference' => 'required|string|max:255',
            'destinations' => 'required|string|max:2000',
            'notes' => 'nullable|string|max:5000',
        ]);

        $this->guardRecent($request);

        $activities = TourActivity::query()
            ->whereIn('id', $validated['activity_ids'])
            ->ordered()
            ->get(['id', 'title']);

        $primaryId = filled($validated['activity_id'] ?? null)
            ? (int) $validated['activity_id']
            : (int) $activities->first()?->id;

        $booking = new Booking();
        $booking->names = $validated['names'];
        $booking->email = $validated['email'];
        $booking->phone = $validated['phone'];
        $booking->message = $validated['notes'] ?? null;
        $booking->checkin_date = $validated['activity_start'];
        $booking->checkout_date = $validated['activity_end'];
        $booking->adults = (int) $validated['guests'];
        $booking->children = 0;
        $booking->rooms = 1;
        $booking->party_type = (int) $validated['guests'] > 2 ? 'group' : 'individual';
        $booking->status = 'pending';
        $booking->booking_type = 'online';
        $booking->reservation_channel = 'website';
        $booking->guest_source = 'unspecified';
        $booking->payment_status = 'pending';
        $booking->paid_amount = 0;
        $booking->total_amount = 0;
        $booking->balance_amount = 0;
        $booking->reservation_type = 'tour_activity';
        $booking->tour_activity_id = $primaryId ?: null;
        $booking->room_id = null;
        $booking->facility_id = null;
        $booking->accommodation_preference = $validated['accommodation_preference'];
        $booking->transport_preference = $validated['transport_preference'];
        $booking->destinations = $validated['destinations'];
        $booking->selected_tours = $activities->map(fn (TourActivity $activity) => [
            'id' => $activity->id,
            'title' => $activity->title,
        ])->values()->all();
        if (\Illuminate\Support\Facades\Schema::hasColumn('bookings', 'currency')) {
            $booking->currency = hotel_currency();
        }
        if (auth()->check()) {
            $booking->user_id = auth()->id();
        }
        $booking->manage_token = app(GuestRequestService::class)->makeToken();
        $booking->save();

        return $this->deliver($booking);
    }

    private function deliver(Booking $booking): array
    {
        $booking->load(['room', 'facility', 'tourActivity']);

        $adminSent = SiteNotificationMail::sendToTeam(new BookingSubmittedAdminMail($booking));
        $guestSent = SiteNotificationMail::sendToGuest($booking->email, new BookingSubmittedGuestMail($booking));

        return [
            'booking' => $booking,
            'admin_sent' => $adminSent,
            'guest_sent' => $guestSent,
        ];
    }

    private function guardRecent(Request $request): void
    {
        if ($request->boolean('confirm_new_request')) {
            return;
        }

        $recent = app(GuestRequestService::class)->recentForEmail((string) $request->input('email'));
        if ($recent) {
            throw new RecentRequestExists($recent);
        }
    }

    public function createBooking(Request $request): Booking
    {
        $isFacility = $request->filled('facility_id');
        $isTourActivity = $request->filled('tour_activity_id');

        $rules = [
            'names' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', new ActiveEmail],
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
            'country' => 'nullable|string|max:100',
            'stay_reason' => 'nullable|string|max:50',
            'party_type' => 'nullable|in:individual,group',
        ];

        if ($isFacility) {
            $rules['facility_id'] = 'required|exists:facilities,id';
            $rules['reservation_date'] = 'required|date|after_or_equal:today';
            $rules['guests'] = 'required|integer|min:1';
        } else {
            $rules['checkin'] = 'required|date|after_or_equal:today';
            $rules['checkout'] = 'required|date|after:checkin';
            $rules['adults'] = 'required|integer|min:1';
            $rules['children'] = 'nullable|integer|min:0';
            $rules['extra_beds'] = 'nullable|integer|min:0';
            $rules['rooms'] = 'nullable|integer|min:1';

            if ($isTourActivity) {
                $rules['tour_activity_id'] = 'required|exists:tour_activities,id';
            } else {
                $rules['room_id'] = 'required|exists:rooms,id';
            }
        }

        $request->validate($rules);

        if (! $isFacility) {
            $this->guardRecent($request);
        }

        $booking = new Booking();
        $booking->names = $request->input('names');
        $booking->email = $request->input('email');
        $booking->phone = $request->input('phone');
        $booking->country = $request->input('country');
        $booking->stay_reason = $request->input('stay_reason');
        $booking->party_type = $request->input('party_type', 'individual') ?: 'individual';
        $booking->message = $request->input('message');

        if (! $isFacility && ! $isTourActivity && $request->filled('extra_beds') && (int) $request->input('extra_beds') > 0) {
            $booking->message = trim(($booking->message ?? '')."\nExtra beds requested: ".(int) $request->input('extra_beds'));
        }

        if ($isFacility) {
            $booking->checkin_date = $request->input('reservation_date');
            $booking->checkout_date = $request->input('reservation_date');
            $booking->adults = $request->input('guests');
            $booking->children = 0;
        } else {
            $booking->checkin_date = $request->input('checkin');
            $booking->checkout_date = $request->input('checkout');
            $booking->adults = $request->input('adults');
            $booking->children = $request->input('children') ?? 0;
            $booking->rooms = $request->input('rooms') ?? 1;
        }

        $booking->status = 'pending';
        $booking->booking_type = 'online';
        $booking->reservation_channel = 'website';
        $booking->guest_source = 'unspecified';
        if (\Illuminate\Support\Facades\Schema::hasColumn('bookings', 'currency')) {
            $booking->currency = hotel_currency();
        }
        $booking->payment_status = 'pending';
        $booking->paid_amount = 0;

        if (auth()->check()) {
            $booking->user_id = auth()->id();
        }

        if ($isTourActivity) {
            $activity = TourActivity::query()->find($request->input('tour_activity_id'));
            $booking->tour_activity_id = $request->input('tour_activity_id');
            $booking->reservation_type = 'tour_activity';
            $booking->room_id = null;
            $booking->facility_id = null;
            $booking->total_amount = 0;
            $booking->balance_amount = 0;
            $booking->selected_tours = $activity ? [[
                'id' => $activity->id,
                'title' => $activity->title,
            ]] : [];
        } elseif ($isFacility) {
            $booking->facility_id = $request->input('facility_id');
            $booking->reservation_type = 'facility';
            $booking->room_id = null;
            $booking->tour_activity_id = null;
            $booking->total_amount = 0;
            $booking->balance_amount = 0;
        } else {
            $booking->room_id = $request->input('room_id');
            $booking->reservation_type = 'room';
            $booking->facility_id = null;
            $booking->tour_activity_id = null;

            $room = Room::findOrFail($request->input('room_id'));
            $adults = (int) $request->input('adults');
            $children = (int) ($request->input('children') ?? 0);
            $extraBeds = (int) ($request->input('extra_beds') ?? 0);
            $roomCount = max(1, (int) ($request->input('rooms') ?? 1));

            app(RoomAvailabilityService::class)->assertRequestFits(
                $room,
                $request->input('checkin'),
                $request->input('checkout'),
                $roomCount,
                $adults,
                $children
            );

            $quote = app(RoomDiscountService::class)->quote(
                $room,
                $request->input('checkin'),
                $request->input('checkout'),
                $adults,
                $children,
                $roomCount,
                $extraBeds,
                $request->input('board'),
                $request->input('occupancy'),
                $booking->currency ?? null
            );
            $booking->total_amount = (int) round($quote['total']);
            $booking->list_amount = (int) round($quote['list_total']);
            $booking->discount_amount = (int) round($quote['discount_total']);
            $booking->discount_label = $quote['label'] !== '' ? $quote['label'] : null;
            if (\Illuminate\Support\Facades\Schema::hasColumn('bookings', 'board')) {
                $booking->board = filled($quote['board'] ?? null) ? $quote['board'] : ($request->input('board') ?: null);
                $booking->occupancy = filled($quote['occupancy'] ?? null) ? $quote['occupancy'] : ($request->input('occupancy') ?: null);
            }
            $booking->balance_amount = $booking->total_amount;
        }

        if (in_array($booking->reservation_type, ['room', 'tour_activity'], true)) {
            $booking->manage_token = app(GuestRequestService::class)->makeToken();
        }

        $booking->save();

        return $booking;
    }

    public static function hotelName(): string
    {
        $setting = Setting::first();
        $name = trim((string) ($setting?->company ?? ''));

        return $name !== '' ? $name : (string) config('mail.from.name', config('app.name'));
    }

    public static function adminSubject(Booking $booking): string
    {
        $typeLabel = match ($booking->reservation_type ?? 'room') {
            'facility' => 'Facility booking',
            'tour_activity' => 'Tour request',
            default => 'Room booking',
        };

        return $typeLabel.' — '.$booking->names.' ('.$booking->id.')';
    }

    public static function guestSubject(): string
    {
        return 'We received your reservation — '.self::hotelName();
    }
}
