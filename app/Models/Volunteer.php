<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
