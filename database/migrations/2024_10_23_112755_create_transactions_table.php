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
        // database/migrations/xxxx_xx_xx_create_transactions_table.php
        // Migration pour la table transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributeur_id')->constrained('distributeurs')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->decimal('montant', 15, 2);
            $table->enum('type', ['depot', 'retrait']);
            $table->boolean('annule')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
