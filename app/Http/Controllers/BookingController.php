<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'checkin_date' => 'required|date',
            'lama_inap' => 'required|numeric|min:1',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $checkoutDate = Carbon::parse($validated['checkin_date'])
            ->addMonths((int) $validated['lama_inap']); // <- pastikan pakai addMonths()

        $booking = Booking::create([
            'room_id' => $validated['room_id'],
            'user_id' => Auth::id(),
            'checkin_date' => $validated['checkin_date'],
            'checkout_date' => $checkoutDate,
        ]);

        return redirect()->route('booking.show', $booking->id);
    }

    public function show($id)
    {
        $booking = Booking::with('room.floor.building')->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $room = $booking->room;
        $floor = $room->floor;

        $usedGuests = Booking::where('room_id', $room->id)
            ->where('status', 'booked')
            ->where('id', '!=', $booking->id)
            ->sum('total_guest');

        $maxAvailable = max($floor->max_capacity - $usedGuests, 0) + $booking->total_guest;

        $validated = $request->validate([
            'total_guest' => "required|integer|min:1|max:$maxAvailable",
        ]);

        $booking->update([
            'total_guest' => $validated['total_guest'],
        ]);

        return redirect()->back()->with('success', 'Booking updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
