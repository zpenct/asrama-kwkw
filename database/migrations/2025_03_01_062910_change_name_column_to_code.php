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
            $table->renameColumn('name', 'code');
            $table->integer('floor');
            $table->unique(['code', 'floor', 'building_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('code', function (Blueprint $table) {
            $table->renameColumn('code', 'name');
            $table->dropColumn('floor');
            $table->dropUnique(['code', 'floor', 'building_id']);
        });
    }
};
