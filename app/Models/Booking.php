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
        'status',
        'expired_at',
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

    public function transaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }

    public function getComputedStatusAttribute()
    {
        if ($this->status === 'pending' && now()->greaterThan($this->expired_at)) {
            return 'expired';
        }

        return $this->status;
    }

    public function getLamaInapBulanAttribute(): string
    {
        if (! $this->checkin_date || ! $this->checkout_date) {
            return '-';
        }

        $checkin = \Carbon\Carbon::parse($this->checkin_date);
        $checkout = \Carbon\Carbon::parse($this->checkout_date);
        $months = $checkin->diffInMonths($checkout);

        return $months.' bulan';
    }
}
