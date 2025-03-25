<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'max_capacity')) {
                $table->dropColumn('max_capacity');
            }

            if (Schema::hasColumn('rooms', 'price')) {
                $table->dropColumn('price');
            }

            $table->dropUnique(['code', 'floor', 'building_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('max_capacity')->default(0);
            $table->decimal('price', 10, 2)->default(0);

            $table->unique(['code', 'floor', 'building_id']);
        });
    }
};
