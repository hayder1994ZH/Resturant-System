<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted', 'created_at', 'updated_at'
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
