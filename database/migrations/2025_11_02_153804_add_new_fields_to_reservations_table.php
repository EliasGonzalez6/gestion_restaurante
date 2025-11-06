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
        Schema::table('reservations', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('reservations', 'reservation_date')) {
                $table->date('reservation_date')->after('user_id');
            }
            if (!Schema::hasColumn('reservations', 'number_of_people')) {
                $table->integer('number_of_people')->after('reservation_time');
            }
            if (!Schema::hasColumn('reservations', 'observations')) {
                $table->text('observations')->nullable()->after('number_of_people');
            }
            if (!Schema::hasColumn('reservations', 'completion_status')) {
                $table->enum('completion_status', ['completada', 'no_completada', 'pendiente'])->default('pendiente')->after('status');
            }
            if (!Schema::hasColumn('reservations', 'managed_by')) {
                $table->foreignId('managed_by')->nullable()->after('completion_status')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('reservations', 'canceled_at')) {
                $table->timestamp('canceled_at')->nullable()->after('managed_by');
            }
            
            // Modificar el enum de status si es necesario (agregando 'cancelada')
            DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pendiente', 'aceptada', 'rechazada', 'cancelada') DEFAULT 'pendiente'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'number_of_people',
                'observations',
                'completion_status',
                'managed_by',
                'canceled_at'
            ]);
            
            if (Schema::hasColumn('reservations', 'reservation_date')) {
                $table->dropColumn('reservation_date');
            }
        });
    }
};
