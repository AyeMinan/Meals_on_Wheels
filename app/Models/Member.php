<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [

        'first_name',
        'last_name',
        'gender',
        'age',
        'emergency_contact_number',
        'date_of_birth',
        'dietary_restriction',
        'user_id'
    ];
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function orders(){
        return $this->hasMany(Member::class);
    }

}
