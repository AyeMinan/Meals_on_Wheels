<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Caregiver extends Model
{
    use HasFactory;
    protected $fillable = ['contact_information', 'relationship_to_member'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
