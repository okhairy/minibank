<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'photo',
        'date_naissance',
        'adresse',
        'num_carte_identite',
        'status',
        'solde'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'solde' => 'decimal:2'
    ];

    public function transactionsEnvoyees()
    {
        return $this->hasMany(Transaction::class, 'expediteur_id');
    }

    public function transactionsRecues()
    {
        return $this->hasMany(Transaction::class, 'destinataire_id');
    }

    public function getNomCompletAttribute()
    {
        return "{$this->nom} {$this->prenom}";
    }
}