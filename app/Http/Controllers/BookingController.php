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
            ->addMonths((int) $validated['lama_inap']);

        $booking = Booking::create([
            'room_id' => $validated['room_id'],
            'user_id' => Auth::id(),
            'checkin_date' => $validated['checkin_date'],
            'checkout_date' => $checkoutDate,
            'status' => 'pending',
            'expired_at' => now()->addHours(24),
        ]);

        return redirect()->route('booking.show', $booking->id);
    }

    public function show($id)
    {
        $booking = Booking::with('room.floor.building')->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    public function showTransactions()
    {
        $booking = Booking::with('room')->where('user_id', Auth::id())->get();

        return view('transactions.page', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        // Cek apakah sudah punya transaksi aktif
        $latestTransaction = $booking->transactions()->latest()->first();

        if ($latestTransaction && in_array($latestTransaction->status, ['waiting_payment', 'waiting_verification'])) {
            return redirect()->route('transactions.upload', $latestTransaction->id);
        }

        // Hitung ulang total amount
        $checkin = \Carbon\Carbon::parse($booking->checkin_date);
        $checkout = \Carbon\Carbon::parse($booking->checkout_date);
        $durationInYears = $checkin->floatDiffInRealYears($checkout);

        $totalAmount = $booking->room->floor->price * $booking->total_guest * $durationInYears;

        $transaction = \App\Models\Transaction::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'booking_id' => $booking->id,
            'amount' => $totalAmount,
            'status' => 'waiting_payment',
        ]);

        return redirect()->route('transactions.upload', $transaction->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
