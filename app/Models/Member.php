<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
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
        'gender',
        'age',
        'phone_number',
        'emergency_contact_number',
        'date_of_birth',
        'address',
        'dietary_restriction',
        'image',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
