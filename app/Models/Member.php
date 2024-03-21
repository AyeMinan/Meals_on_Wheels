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

    public function user(){
        return $this->belongsTo(User::class);
    }
}
