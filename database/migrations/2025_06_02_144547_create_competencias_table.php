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
        Schema::create('competencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')
                ->comment('Nombre de la competencia');

            $table->text('descripcion')

                ->comment('Descripción opcional de la competencia');

            $table->date('fecha_inicio')

                ->comment('Fecha de inicio de la comptencia');

            $table->date('fecha_fin')

                ->comment('Fecha de fin de la comptencia');

            $table->string('codigo_categoria', 50)
                ->comment('Código de la categoría relacionada a la competencia');

            $table->foreign('codigo_categoria')
                ->references('catalogo_codigo')
                ->on('catalogos')
                ->onUpdate('cascade')
                ->onDelete('restrict');

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
        Schema::dropIfExists('competencias');
    }
};
