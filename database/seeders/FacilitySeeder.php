<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            ['name' => 'WiFi', 'description' => 'Akses internet cepat dan stabil'],
            ['name' => 'CCTV', 'description' => 'Keamanan terjamin dengan CCTV 24 jam'],
            ['name' => 'AC', 'description' => 'Pendingin ruangan untuk kenyamanan'],
            ['name' => 'Parkir', 'description' => 'Area parkir luas untuk kendaraan'],
            ['name' => 'Lift', 'description' => 'Fasilitas lift untuk kemudahan akses'],
            ['name' => 'Keamanan 24 Jam', 'description' => 'Petugas keamanan siap siaga'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
