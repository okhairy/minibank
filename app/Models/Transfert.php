<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    protected $table = 'transfert'; // Nom explicite de la table

    protected $fillable = [
        'compte_id',
        'montant',
        'type',
        'description',
        'status', // Assurez-vous d'inclure 'status' dans les champs remplissables
    ];

    // Définir les constantes pour les statuts
    const STATUS_EN_ATTENTE = 'en_attente';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}