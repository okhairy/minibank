<?php
// app/Models/Distributeur.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributeur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'solde'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
