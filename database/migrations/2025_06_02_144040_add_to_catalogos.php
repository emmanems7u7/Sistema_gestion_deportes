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
        Schema::table('catalogos', function (Blueprint $table) {
            $table->unique('catalogo_codigo', 'catalogos_catalogo_codigo_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->dropUnique('catalogos_catalogo_codigo_unique');
        });
    }
};
