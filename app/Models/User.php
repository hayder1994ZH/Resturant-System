<?php

namespace App\Models;

use App\Helpers\Utilities;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
   
    protected $guarded =[];
    protected $hidden = [
        'is_deleted',
        'password',
    ];

    // protected $appends = ['imageUser', 'channels'];
    // public function getImageUserAttribute(){
    //     return ($this->image != null)? Utilities::hostImages() . Utilities::$imageBuket . $this->image:null;
    // }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function rules(){//get  user rule
        return $this->belongsTo(Rules::class, 'rule_id');
    }
}
