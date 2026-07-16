<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Definimos el rol: 'admin' (control total) o 'empleado' (personalizable)
            $table->string('role')->default('empleado')->after('password');
            
            // Permisos específicos si es un empleado
            $table->boolean('can_create')->default(false)->after('role');
            $table->boolean('can_edit')->default(false)->after('can_create');
            $table->boolean('can_delete')->default(false)->after('can_edit');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'can_create', 'can_edit', 'can_delete']);
        });
    }
};