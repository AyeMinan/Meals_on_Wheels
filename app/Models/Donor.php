<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Donor\App\Models\Donation;

class Donor extends Model
{
    use HasFactory;
    protected $fillable = [

        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function donation()
    {
        return $this->hasMany(Donation::class);
    }
}
