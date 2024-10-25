<?php
// app/Models/Distributeur.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Importez la bonne classe
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash; // Ajoutez cette ligne

class Distributeur extends Authenticatable // Changez Model en Authenticatable
{
    use HasFactory;

    protected $fillable = ['nom', 'email', 'password', 'solde']; // Ajoutez 'email' et 'password'

    protected $hidden = ['password']; // Cachez le mot de passe pour les requêtes

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value); // Utilisez Hash ici
    }
    
}
