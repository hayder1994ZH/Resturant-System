<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
    public function meal()
    {
        return $this->hasMany(Meals::class);
    }
    public function langBody()
    {
        return $this->hasMany(LangBodys::class, 'tbable_id')->where('tbable_type', 'Categories');
    }
}
