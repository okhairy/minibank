<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactio extends Model
{
    use HasFactory;

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
    }
}