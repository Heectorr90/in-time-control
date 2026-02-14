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
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_hijo_id')
                ->constrained('categoria_hijos')
                ->restrictOnDelete();

            $table->unsignedInteger('codigo');
            $table->string('nombre');
            $table->timestamps();

            $table->unique(['categoria_hijo_id', 'codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategorias');
    }
};
