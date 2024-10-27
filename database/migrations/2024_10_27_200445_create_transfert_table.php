<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfert', function (Blueprint $table) {
            $table->id(); // Crée une colonne 'id' auto-incrémentée
            $table->unsignedBigInteger('compte_id')->nullable(); // Clé étrangère potentielle
            $table->decimal('montant', 10, 2)->nullable(); // Montant du transfert
            $table->string('type', 255)->nullable(); // Type de transfert (dépôt, retrait, etc.)
            $table->text('description')->nullable(); // Description du transfert
            $table->enum('status', ['en_attente', 'completed', 'cancelled'])->default('en_attente'); // Statut du transfert
            $table->timestamps(); // Gère automatiquement les colonnes 'created_at' et 'updated_at'

            // Foreign key constraint vers la table 'comptes' (si applicable)
            $table->foreign('compte_id')->references('id')->on('comptes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfert');
    }
}
