<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = ['first_name','last_name', 'gender', 'date_of_birth','age', 'emergency_contact_number', 'dietary_restriction', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
