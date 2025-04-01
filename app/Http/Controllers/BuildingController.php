<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Floor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    public function show($id, Request $request)
    {

        $building = Building::with('facilities')->findOrFail($id);

        $checkin = $request->filled('checkin_date') ? Carbon::parse($request->checkin_date) : null;
        $checkout = $checkin ? (clone $checkin)->addMonths((int) $request->lama_inap) : null;

        $query = Floor::where('building_id', $id)->with(['rooms' => function ($roomQuery) use ($checkin, $checkout) {
            $roomQuery->withCount([
                'bookings as booked_guest_count' => function ($q) use ($checkin, $checkout) {

                    $q->whereIn('status', ['pending', 'booked'])
                        ->where(function ($query) {
                            $query->whereNull('expired_at')
                                ->orWhere('expired_at', '>', now());
                        });

                    if ($checkin && $checkout) {
                        $q->where('checkin_date', '<', $checkout)
                            ->where('checkout_date', '>', $checkin);
                    }

                    $q->select(DB::raw('COALESCE(SUM(total_guest), 0)'));
                },
            ]);

            // Sisa filter seperti sebelumnya
            $roomQuery->whereHas('floor', function ($q) {
                $q->whereNotNull('max_capacity');
            });

            if ($checkin && $checkout) {
                $roomQuery->whereRaw('
                (
                    SELECT COALESCE(SUM(total_guest), 0)
                    FROM bookings
                    WHERE bookings.room_id = rooms.id
                        AND bookings.status IN ("pending", "booked")
                        AND (bookings.expired_at IS NULL OR bookings.expired_at > NOW())
                        AND checkin_date < ?
                        AND checkout_date > ?
                ) < (
                    SELECT max_capacity
                    FROM floors
                    WHERE floors.id = rooms.floor_id
                )
            ', [$checkout, $checkin]);
            }
        }]);

        // filter lantai, kode kamar, harga, sorting seperti sebelumnya...
        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }

        if ($request->filled('room_code')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->room_code.'%');
            });
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('sort_by') && in_array($request->sort_by, ['asc', 'desc'])) {
            $query->orderBy('max_capacity', $request->sort_by);
        }

        $floors = $query->get();

        return view('buildings.show', compact('building', 'floors'));
    }
}
