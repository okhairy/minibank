<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clients extends Model
{


    protected $fillable = ['nom', 'prenom', 'email', 'solde', 'numero_compte'];

    public function transactionns()
    {
        return $this->hasMany(Transactionn::class); // Assurez-vous que cela correspond à votre modèle Transactionn
    }
}


