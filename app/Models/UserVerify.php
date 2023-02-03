<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $table = "users_verify";
    
     protected $fillable = [
        'user_id',
        'token',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
