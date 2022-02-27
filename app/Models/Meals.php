<?php

namespace App\Models;

use DateTimeInterface;
use App\Helpers\Utilities;
use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s');
    }

    protected $appends = ['title', 'description'];
    
    public function getTitleAttribute(){
       return Utilities::getTitle('Meals', $this->id);
    }
    public function getDescriptionAttribute(){
        return Utilities::getDescription('Meals', $this->id);
    }
    
    //Relationship
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id');
    }
    public function langBody()
    {
        return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Meals');

    }
    public function extraMeal()
    {
        return $this->hasMany(ExtraMeals::class, 'meal_id');
    }
    public function lang()
    {
        return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Meals')->where('lang_id', Utilities::getLang());
    }
}
