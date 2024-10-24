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
       // database/migrations/xxxx_xx_xx_create_clients_table.php
    Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('numero_compte')->unique();
    $table->decimal('solde', 15, 2)->default(0);
    $table->boolean('bloque')->default(false); // Indicateur si le compte est bloqué
    $table->timestamps();
   });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
