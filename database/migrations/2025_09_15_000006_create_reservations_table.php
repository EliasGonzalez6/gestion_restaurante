<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->integer('number_of_people');
            $table->text('observations')->nullable();
            $table->enum('status', ['pendiente', 'aceptada', 'rechazada', 'cancelada'])->default('pendiente');
            $table->enum('completion_status', ['completada', 'no_completada', 'pendiente'])->default('pendiente');
            $table->foreignId('managed_by')->nullable()->constrained('users')->onDelete('set null'); // Quién aceptó/rechazó
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
