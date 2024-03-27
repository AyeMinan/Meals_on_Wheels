<?php

namespace Modules\Donor\App\Models;

use App\Models\Donor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Donor\Database\factories\DonationFactory;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['amount'];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

}
