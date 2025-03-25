<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    protected $table = 'floors';

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $fillable = [
        'building_id',
        'floor',
        'max_capacity',
        'price',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'floor_id');
    }
}
