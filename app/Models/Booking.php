<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
