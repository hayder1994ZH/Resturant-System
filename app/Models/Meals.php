<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
    //Relationship
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function langBody()
    {
        return $this->hasMany(LangBodys::class, 'tbable_id')->where('tbable_type', 'Meals');
    }
    public function extraMeal()
    {
        return $this->hasMany(ExtraMeals::class, 'meal_id')->where('tbable_type', 'Meals');
    }
}
