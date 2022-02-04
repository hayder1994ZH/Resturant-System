<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ExtraMeals extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted', 'created_at', 'updated_at'
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
    public function meal()
    {
        return $this->belongsTo(Meals::class);
    }
}
