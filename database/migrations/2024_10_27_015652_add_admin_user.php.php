<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
        DB::table('comptes')->insert([
        'nom' => 'Admin',
        'prenom' => 'System',
        'numero_telephone' => '123456789',
        'role' => 'admin',
        'numero_compte' => 'ADMIN001',
        'solde' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
