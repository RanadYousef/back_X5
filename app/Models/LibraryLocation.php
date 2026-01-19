<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryLocation extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
    ];

    protected $appends = ['google_maps_url'];

    public function getGoogleMapsUrlAttribute()
    {
        return 'https://www.google.com/maps?q=' . $this->latitude . ',' . $this->longitude;
    }
}
