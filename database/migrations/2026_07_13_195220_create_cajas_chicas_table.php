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
        Schema::create('cajas_chicas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique(); // Solo se permite un registro de caja chica por fecha
            $table->decimal('monto_inicial', 8, 2)->default(0.00); // Dinero base para dar cambio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas_chicas');
    }
};