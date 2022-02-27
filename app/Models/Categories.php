<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\LangBodys;
use App\Models\Languages;
use App\Helpers\Utilities;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];

    protected $appends = ['title', 'description'];
    
    public function getTitleAttribute(){
       return Utilities::getTitle('Categories', $this->id);
    }
    public function getDescriptionAttribute(){
        return Utilities::getDescription('Categories', $this->id);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
    public function meal()
    {
        return $this->hasMany(Meals::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurants::class);
    }
    
    public function langBody()
    {
        return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Categories');
    }
    public function lang()
    {
        return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Categories')->where('lang_id', Utilities::getLang());
    }
}
