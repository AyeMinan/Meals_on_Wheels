<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'gender', 'date_of_birth','relationship_with_member', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
