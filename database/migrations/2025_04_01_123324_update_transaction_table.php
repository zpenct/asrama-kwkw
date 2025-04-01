<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE transactions MODIFY status 
                ENUM('waiting_payment', 'waiting_verification', 'paid', 'rejected') 
                DEFAULT 'waiting_payment'");

            $table->dropColumn('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE transactions MODIFY status 
                ENUM('waiting_payment', 'waiting_verification', 'paid', 'rejected', 'expired') 
                DEFAULT 'waiting_payment'");

            $table->timestamp('expired_at')->nullable();
        });
    }
};
