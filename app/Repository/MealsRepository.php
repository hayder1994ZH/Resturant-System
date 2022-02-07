<?php
namespace App\Repository;

use App\Models\ExtraMeals;
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
    //Base repo to get all meal languges
    public function getExtraMeal($id){
        return QueryBuilder::for(ExtraMeals::class)
                                ->where('is_deleted', 0)
                                ->where('meal_id', $id)
                                ->orderBy('created_at', 'desc')->get();
    } 
    
    //Base repo to get all items
    public function getListAdmin($skip, $take, $relations, $filter, $id){
        $result = QueryBuilder::for($this->table)
                                ->where('is_deleted', 0)
                                ->where('restaurant_id', $id)
                                ->allowedFilters($filter);
                                if(!empty($relations)){
                                    $result = $result->with($relations);
                                }
        $totalCount = $result->get()->count();
        $resultData = $result->where('is_deleted', 0)
                        ->take($take)
                        ->skip($skip)
                        ->orderBy('created_at', 'desc');
        return [
            'totalCount' => $totalCount,
            'items' => $resultData->get()
        ];
    }
}