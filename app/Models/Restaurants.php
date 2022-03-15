<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted','description'
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function category()
    {
        return $this->hasMany(User::class);
    }
    public function meal()
    {
        return $this->hasMany(User::class);
    }
    public function phones()
    {
        return $this->hasMany(RestaurantPhones::class);
    }
}
