<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cambiar el tipo de dato de reservation_time a TIME
        DB::statement('ALTER TABLE reservations MODIFY reservation_time TIME NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir si es necesario
        DB::statement('ALTER TABLE reservations MODIFY reservation_time TIME NOT NULL');
    }
};
