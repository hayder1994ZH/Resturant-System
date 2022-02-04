<?php

namespace App\Models;

use DateTimeInterface;
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
    //Relationship
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id')->with('langBody');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id');
    }
    public function langBody()
    {
        $lang = request()->header('lang');
        $lang = Languages::where('name', $lang)->first();
        $lang = (!is_null($lang))? $lang->id:null;
        $body = LangBodys::where('lang_id', $lang)->where('tbable_id', $this->id)->where('tbable_type', 'Meals')->first();
        if(!is_null($body)){
            return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Meals')->where('lang_id', $lang);
        }
        return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Meals');

    }
    public function extraMeal()
    {
        return $this->hasMany(ExtraMeals::class, 'meal_id')->where('tbable_type', 'Meals');
    }
}
