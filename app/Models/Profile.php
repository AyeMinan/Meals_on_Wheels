<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory;
    protected $fillable=['image','user_name','address','phone_number','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member(){
        return $this->hasOne(Member::class);
    }

    public function donor(){
        return $this->hasOne(Donor::class);
    }
}
