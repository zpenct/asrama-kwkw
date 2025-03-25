<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_id',
        'user_id',
        'checkin_date',
        'checkout_date',
        'total_guest',
    ];

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
