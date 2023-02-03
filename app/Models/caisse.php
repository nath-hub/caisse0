<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class caisse extends Model
{
    use HasFactory;
    
     protected $fillable = [

    'cnss',
    'nom_prenom',
    'telephone',
    'email',
    'compte_actif',
    'adresse',
    'logo',
    'raison_social',
    'rccm',
    'nif',
    'inviter'
];

public function user()
    {
      return $this->belongsTo(User::class);
    }
}
