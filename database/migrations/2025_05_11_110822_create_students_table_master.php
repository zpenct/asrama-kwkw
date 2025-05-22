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
        Schema::connection('master_db')->create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email_student')->unique();
            $table->string('student_number')->unique();
            $table->string('entry_year');
            $table->string('major');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_table_master');
    }
};
