<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id(); // Crée une colonne id auto-incrémentée
            $table->unsignedBigInteger('user_id'); // Colonne user_id
            $table->enum('type', ['Dépôt', 'Envoi', 'Retrait', 'Annulé']); // Type de transaction
            $table->decimal('montant', 10, 2); // Montant de la transaction
            $table->decimal('fee', 10, 2)->nullable(); // Frais de la transaction (optionnel)
            $table->string('destinataire')->nullable(); // Compte destinataire (optionnel)
            $table->string('sender_name')->nullable(); // Nom de l'expéditeur (optionnel)
            $table->timestamps(); // Créée les colonnes created_at et updated_at

            // Ajout d'une clé étrangère sur user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions'); // Supprime la table transactions
    }
}
