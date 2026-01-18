<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryLocation;

class LibraryLocationSeeder extends Seeder
{
    public function run(): void
    {
        LibraryLocation::create([
            'name' => 'Central Library',
            'address' => 'Damascus, Syria',
            'latitude' => 33.514567,
            'longitude' => 36.276489,
        ]);
    }
}
