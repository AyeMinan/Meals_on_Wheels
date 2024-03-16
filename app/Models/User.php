<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Caregiver;
use Modules\Partner\App\Models\Partner;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'confirm_password',
        'type',
        'role_as',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile(){
        return $this->hasOne(Profile::class,'user_id');
    }

    public function partner(){
        return $this->hasOne(Partner::class,'user_id');
    }
    public function member(){
        return $this->hasOne(Member::class,'user_id');
    }
    public function volunteer(){
        return $this->hasOne(Volunteer::class,'user_id');
    }
    public function caregber(){
        return $this->hasOne(Caregiver::class,'user_id');
    }
    public function donor(){
        return $this->hasOne(Donor::class,'user_id');
    }

<<<<<<< HEAD
=======
    public function caregiver()
    {
        return $this->hasOne(Caregiver::class);
    }

    public function partner(){
        return $this->hasOne(Partner::class);
    }
    public function volunteer(){
        return $this->hasOne(Volunteer::class);

    }
 public function donor(){
        return $this->hasOne(Donor::class);

    }

>>>>>>> develop
}

