<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
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
        'address',
        'phone_number',
        'shop_name',
        'shop_address',
        'image',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
