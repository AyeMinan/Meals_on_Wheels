<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ingredients',
        'allergy_information',
        'nutritional_information',
        'dietary_restrictions',
        'price',
        'is_frozen',
        'delivery_status',
        'temperature',
        'is_preparing',
        'is_finished',
        'is_pickup',
        'is_delivered',
        'image',
        'orderer_id'
    ];
}
