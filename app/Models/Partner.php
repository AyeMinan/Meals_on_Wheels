<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = ['partner_type', 'location'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
