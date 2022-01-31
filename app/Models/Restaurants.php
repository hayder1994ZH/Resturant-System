<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
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
}
