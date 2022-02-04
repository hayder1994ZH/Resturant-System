<?php
namespace App\Repository;

use App\Models\LangBodys;
use Spatie\QueryBuilder\QueryBuilder;

class MealsRepository extends BaseRepository {
 
    //Base repo to get all meal languges
    public function getMealLang($id){
        $result = QueryBuilder::for(LangBodys::class)
                                ->where('is_deleted', 0)
                                ->where('tbable_id', $id)
                                ->where('tbable_type', 'Meals')
                                ->with('lang');
        return $result->orderBy('created_at', 'desc')->get();
    } 
}