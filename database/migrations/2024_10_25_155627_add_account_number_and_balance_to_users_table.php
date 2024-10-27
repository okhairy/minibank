<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountNumberAndBalanceToUsersTable extends Migration
{
    /**
     * Exécutez la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_number')->nullable(); // Ajoute une colonne account_number
            $table->decimal('balance', 10, 2)->default(0); // Ajoute une colonne balance
        });
    }

    /**
     * Rétrograde la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_number'); // Supprime la colonne account_number
            $table->dropColumn('balance'); // Supprime la colonne balance
        });
    }
}
