<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comptes', function (Blueprint $table) {
            $table->id(); // colonne 'id' auto-incrémentée
            $table->string('nom', 255);
            $table->string('prenom', 255);
            $table->string('numero_telephone', 15)->nullable();
            $table->date('date_naissance');
            $table->string('adresse', 255);
            $table->string('numero_carte_identite', 50);
            $table->enum('status', ['actif', 'bloque'])->default('actif');
            $table->timestamp('created_at')->useCurrent(); // Définit la valeur par défaut à la date actuelle
            $table->timestamp('updated_at')->useCurrentOnUpdate(); // Mise à jour automatique lors de chaque update
            $table->string('role', 50);
            $table->string('numero_compte', 50);
            $table->decimal('solde', 10, 2)->default(0.00); // Solde avec précision 10,2 et valeur par défaut 0.00
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comptes');
    }
}
