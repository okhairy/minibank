<?php
<<<<<<< HEAD
// app/Models/Transaction.php
=======

>>>>>>> 5c4681e9d94de6ca7cf2b9c21268b363c496eb1a
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = ['distributeur_id', 'client_id', 'montant', 'type', 'annule'];

    public function distributeur()
    {
        return $this->belongsTo(Distributeur::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
=======
    protected $fillable = [
        'user_id',
        'type',
        'montant',
        'fee',
        'destinataire',
        'sender_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
>>>>>>> 5c4681e9d94de6ca7cf2b9c21268b363c496eb1a
    }
}
