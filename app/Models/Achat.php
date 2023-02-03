<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'numero_transaction',
        'mode_paiement',
        'detail_achat',
        'tiers',
        'compte_tresorerie',
        'controle_equilibre',
        'attache',
        'solde_tiers',
        'solde_tresorerie',
        'annalation',
        'date_annulation',
        'total_achat'
        ]
}
