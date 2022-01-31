<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraMeals extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted', 'created_at', 'updated_at'
    ];
    
    public function meal()
    {
        return $this->belongsTo(Meals::class);
    }
}
