<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_transactionns_table.php
//table pour la transaction des clients gerer par moustapha.
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionnsTable extends Migration
{
    public function up()
    {
        Schema::create('transactionns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID de l'utilisateur effectuant la transaction
            $table->string('type'); // Type de transaction (Dépôt, Envoi, etc.)
            $table->decimal('montant', 10, 2); // Montant de la transaction
            $table->string('destinataire')->nullable(); // Numéro de compte destinataire (si applicable)
            $table->string('sender_name')->nullable(); // Nom de l'expéditeur (si applicable)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactionns');
    }
}
