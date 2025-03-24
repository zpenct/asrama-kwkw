<?php

namespace App\Models;

use App\Models\Floor;
use App\Models\Booking;
use App\Models\Building;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = ['building_id', 'floor_id', 'code'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }


    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}