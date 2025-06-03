<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documento_competencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competencia_id')
                ->constrained('competencias')
                ->onDelete('cascade')->comment('ID de la competencia relacionada');
            $table->string('documento_nombre')->comment('Nombre del documento');
            $table->string('documento_ruta')->comment('Ruta del documento');
            $table->string('accion_usuario', 20)
                ->comment('Usuario que realizó la acción');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_competencias');
    }
};
