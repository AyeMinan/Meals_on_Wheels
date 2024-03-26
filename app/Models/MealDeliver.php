<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealDeliver extends Model
{
    use HasFactory;
    protected $fillable=['partner_id','volunteer_id','delivery_date'];
}
