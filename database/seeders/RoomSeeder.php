<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $floors = Floor::all();

        foreach ($floors as $floor) {
            for ($i = 1; $i <= rand(3, 7); $i++) {
                Room::create([
                    'building_id' => $floor->building_id,
                    'floor_id' => $floor->id, 
                    'code' => 'R-DUM' . $floor->floor . $i,
                ]);
            }
        }
    }
}