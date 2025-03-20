<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            [
                'name' => 'Gedung A',
                'type' => 'PUTRA',
                'description' => 'Gedung khusus mahasiswa putra',
                'image_url' => 'buildings/gedung_a.jpg',
            ],
            [
                'name' => 'Gedung B',
                'type' => 'PUTRI',
                'description' => 'Gedung khusus mahasiswa putri',
                'image_url' => 'buildings/gedung_b.jpg',
            ],
            [
                'name' => 'Gedung C',
                'type' => 'PUTRA',
                'description' => 'Gedung dengan fasilitas lengkap',
                'image_url' => 'buildings/gedung_c.jpg',
            ],
        ];

        foreach ($buildings as $data) {
            $building = Building::create($data);

            // Hubungkan dengan fasilitas secara acak
            $facilities = Facility::inRandomOrder()->take(rand(2, 4))->pluck('id');
            $building->facilities()->attach($facilities);
        }
    }
}