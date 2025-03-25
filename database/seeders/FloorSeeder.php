<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Floor;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::all();

        foreach ($buildings as $building) {
            for ($i = 1; $i <= rand(2, 5); $i++) {
                Floor::create([
                    'building_id' => $building->id,
                    'floor' => $i,
                    'price' => rand(500000, 2000000),
                    'max_capacity' => rand(50, 200),
                ]);
            }
        }
    }
}
