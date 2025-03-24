<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use App\Http\Controllers\Controller;

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
                    $q->where('status', 'booked');
        
                    if ($checkin && $checkout) {
                        $q->where('checkin_date', '<', $checkout)
                          ->where('checkout_date', '>', $checkin);
                    }
        
                    $q->select(DB::raw('COALESCE(SUM(total_guest), 0)'));
                }
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
                            AND bookings.status = "booked"
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
        


        // $query = Floor::where('building_id', $id)->with(['rooms' => function ($roomQuery) use ($request) {
        //     // Booking filter aktif (checkin & lama_inap dipilih)
        //     if ($request->filled('checkin_date') && $request->filled('lama_inap')) {
        //         $checkin = Carbon::parse($request->checkin_date);
        //         $checkout = (clone $checkin)->addMonths((int) $request->lama_inap);

        //         // Cek apakah ruangan belum dipakai pada tanggal tersebut
        //         $roomQuery->whereDoesntHave('bookings', function ($bookingQuery) use ($checkin, $checkout) {
        //             $bookingQuery->where('status', 'booked')
        //                 ->where(function ($q) use ($checkin, $checkout) {
        //                     $q->where('checkin_date', '<', $checkout)
        //                         ->where('checkout_date', '>', $checkin);
        //                 });
        //         });
        //     }

        //     // Filter hanya kamar yang masih punya kapasitas guest tersisa
        //     $roomQuery->whereHas('floor', function ($q) {
        //         $q->whereNotNull('max_capacity');
        //     })->where(function ($q) {
        //         $q->whereDoesntHave('bookings', function ($bq) {
        //             $bq->where('status', 'booked');
        //         })->orWhereRaw('
        //     (SELECT COALESCE(SUM(total_guest), 0)
        //      FROM bookings
        //      WHERE bookings.room_id = rooms.id AND bookings.status = "booked")
        //     < (
        //         SELECT max_capacity
        //         FROM floors
        //         WHERE floors.id = rooms.floor_id
        //     )
        // ');
        //     });
        // }]);


        // filter lantai, kode kamar, harga, sorting seperti sebelumnya...
        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }

        if ($request->filled('room_code')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->room_code . '%');
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