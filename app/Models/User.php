<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Correct
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'cin',
        'date_naissance',
        'adresse',
        'role',
        'photo',
        'email',
        'password',
        'account_number', // Assurez-vous que ce champ existe dans la base de données
        'balance',        // Assurez-vous que ce champ existe dans la base de données
    ];

    /**
     * Les attributs qui devraient être cachés pour les tableaux.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à muter.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance' => 'decimal:2', // Assurez-vous que le solde est stocké en tant que décimal
    ];

    /**
     * Relation avec le modèle Transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    
    
    /**
     * Générer un numéro de compte unique.
     *
     * @return string
     */
    public static function generateAccountNumber()
    {
        // Générer un numéro de compte unique
        return strtoupper(uniqid('ACC'));
    }

    /**
     * Mutateur pour le mot de passe.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    // Dans le modèle User
public static function findByAccountNumber($accountNumber)
{
    return self::where('account_number', $accountNumber)->first();
}
public function destinataire()
{
    return $this->belongsTo(User::class, 'destinataire_id');  // ou utilisez la clé étrangère appropriée
}
}
