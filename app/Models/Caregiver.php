<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'email',
        'user_name',
        'password',
        'confirm_password',
        'first_name',
        'last_name',
        'date_of_birth',
        'relationship_with_member',
        'image',
        'gender',
        'user_id'
    ];


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

  
}
