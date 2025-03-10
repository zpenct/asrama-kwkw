<?php

namespace App\Models;

use App\Models\Room;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'description', 'type', 'image_url'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }
    
}