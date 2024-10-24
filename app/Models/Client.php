<?php
// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['numero_compte', 'solde', 'bloque'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
