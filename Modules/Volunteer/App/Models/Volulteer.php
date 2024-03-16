<?php

namespace Modules\Volunteer\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Volunteer\Database\factories\VolulteerFactory;

class Volulteer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): VolulteerFactory
    {
        //return VolulteerFactory::new();
    }
}
