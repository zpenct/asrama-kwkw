<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    public function index()
    {
        $s3 = Storage::disk('s3');

        $floorPaths = array_slice($s3->files('floors'), 0, 10);
        $buildingPaths = array_slice($s3->files('buildings'), 0, 10);
        $facilityPaths = array_slice($s3->files('facilities'), 0, 10);

        return view('gallery.list', [
            'floorPaths' => $floorPaths,
            'buildingPaths' => $buildingPaths,
            'facilityPaths' => $facilityPaths,
        ]);
    }
}
