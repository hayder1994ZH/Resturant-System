<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
    public function meal()
    {
        return $this->belongsTo(Meals::class);
    }
}
