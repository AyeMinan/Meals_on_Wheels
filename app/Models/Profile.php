<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
 
class Profile extends Model
{
    use HasFactory;
    protected $fillable=['user_name','image','phone_number','address','user_id'];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function member(){
        return $this->hasOne(Member::class);
    }
 
    public function caregiver(){
        return $this->hasOne(Caregiver::class);
    }
 
}
