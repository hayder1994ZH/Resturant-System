<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurants::class);
    }
}
