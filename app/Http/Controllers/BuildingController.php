<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class BuildingController extends Controller
{
    public function index() {}

 
    public function store(Request $request)
    {
        //
    }

    public function show($id, Request $request)
    {
        // Ambil data gedung beserta lantai dan ruangan
        $building = Building::with('facilities')->findOrFail($id);

        // Query lantai berdasarkan gedung
        $query = Floor::where('building_id', $id)->with('rooms');

        //  Filter berdasarkan nomor lantai
        if ($request->has('floor') && $request->floor != '') {
            $query->where('floor', $request->floor);
        }

        //  Search berdasarkan kode ruangan
        if ($request->has('room_code') && $request->room_code != '') {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->room_code . '%');
            });
        }

        //  Filter berdasarkan rentang harga lantai
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        //  Sorting berdasarkan kapasitas maksimum
        if ($request->has('sort_by') && in_array($request->sort_by, ['asc', 'desc'])) {
            $query->orderBy('max_capacity', $request->sort_by);
        }

        $floors = $query->get();

        return view('buildings.show', compact('building', 'floors'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}