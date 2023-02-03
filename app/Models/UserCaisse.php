<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCaisse extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $table = "UserCaisse";
    
    
    protected $fillable = [
    'user_id',
    'caisse_id',
    'admin',
    'compta',
    'stock',
    'com',
    'paie',
    'immos',
    'budget',
    'rap',
    ];


    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function caisse()
    {
        return $this->belongsTo(caisse::class);
    }
}
