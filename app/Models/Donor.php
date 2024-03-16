<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
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
        'phone_number',
        'date_of_birth',
        'address',
        'image',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
