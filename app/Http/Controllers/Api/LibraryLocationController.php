<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LibraryLocation;

class LibraryLocationController extends Controller
{
    public function show()
    {
        $location = LibraryLocation::first();

        return response()->json([
            'success' => true,
            'data' => $location,
        ]);
    }
}
