<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'transactions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'booking_id',
        'amount',
        'status',
        'payment_proof',
        'uploaded_at',
        'verified_at',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->booking->user;
    }
}
