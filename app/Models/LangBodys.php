<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class LangBodys extends Model
{
    protected $guarded =[];
    
    protected $hidden =[
        'is_deleted'
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function lang()
    {
        return $this->belongsTo(Languages::class, 'lang_id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class, 'tbable_id');
    }
}
