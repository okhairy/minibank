<?php
// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['distributeur_id', 'client_id', 'montant', 'type', 'annule'];

    public function distributeur()
    {
        return $this->belongsTo(Distributeur::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
