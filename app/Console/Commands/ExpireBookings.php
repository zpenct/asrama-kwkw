<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:expire-bookings';
    protected $signature = 'expire:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('[ExpireBookings] Cronjob started at '.now());

        $expired = Booking::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);

        Log::info("[ExpireBookings] Total bookings expired: {$expired}");
    }
}
