<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compte extends Model
{
    use HasFactory;

    protected $table = 'comptes';

    protected $fillable = [
        'nom',
        'prenom',
        'numero_telephone',
        'date_naissance',
        'adresse',
        'numero_carte_identite',
        'numero_compte', // Champ pour le numéro de compte
        'role', // Ajout du champ pour le rôle (client ou distributeur)
        'photo',
        'statut', // Champ pour le statut du compte
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function generateAccountNumber($role)
    {
        $prefix = $role === 'client' ? 'CLI' : 'DIS'; // Préfixe selon le rôle
        $number = rand(100000, 999999); // Génération d'un nombre aléatoire

        return $prefix . $number; // Retourner le numéro de compte
    }
}