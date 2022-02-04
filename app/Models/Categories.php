<?php

namespace App\Models;

use DateTimeInterface;
use App\Models\LangBodys;
use App\Models\Languages;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
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
        $lang = request()->header('lang');
        $lang = Languages::where('name', $lang)->first();
        $lang = (!is_null($lang))? $lang->id:null;
        $body = LangBodys::where('lang_id', $lang)->where('tbable_id', $this->id)->where('tbable_type', 'Categories')->first();
        if(!is_null($body)){
            return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Categories')->where('lang_id', $lang);
        }
        return $this->hasOne(LangBodys::class, 'tbable_id')->where('tbable_type', 'Categories');
    }
}
