<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    public function index()
    {
        $s3 = Storage::disk('s3');

        $floorPaths = $s3->files('floors');
        $buildingPaths = $s3->files('buildings');
        $facilityPaths = $s3->files('facilities');

        return view('gallery.list', [
            'floorPaths' => $floorPaths,
            'buildingPaths' => $buildingPaths,
            'facilityPaths' => $facilityPaths,
        ]);
    }
}
