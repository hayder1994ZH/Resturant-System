<?php
namespace App\Repository;

use App\Models\LangBodys;
use App\Helpers\Utilities;
use App\Models\ExtraMeals;
use Spatie\QueryBuilder\QueryBuilder;

class MealsRepository extends BaseRepository {
 
    //Base repo to get all meal languges
    public function getMealLang($id){
        $result = QueryBuilder::for(LangBodys::class)
                                ->where('is_deleted', 0)
                                ->where('tbable_id', $id)
                                ->where('tbable_type', 'Meals')
                                ->with('lang');
        return $result->orderBy('id', 'desc')->get();
    } 
    //Base repo to get all meal languges
    public function getExtraMeal($id){
        return QueryBuilder::for(ExtraMeals::class)
                                ->where('is_deleted', 0)
                                ->where('meal_id', $id)
                                ->orderBy('id', 'desc')->get();
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
                        ->orderBy('id', 'desc');
        return [
            'totalCount' => $totalCount,
            'items' => $resultData->get()
        ];
    }

    //======= web repo 
    
    //Base repo to get all items
    public function getWeb($skip, $take, $filter, $id){
        $result = QueryBuilder::for($this->table)
                                ->where('is_deleted', 0)
                                ->where('restaurant_id', $id)
                                ->whereHas('lang', function($q){
                                    $q->where('lang_id', Utilities::getLang());
                                })
                                ->allowedFilters($filter);
        $totalCount = $result->get()->count();
        $resultData = $result->where('is_deleted', 0)
                        ->take($take)
                        ->skip($skip)
                        ->orderBy('id', 'desc');
        return [
            'totalCount' => $totalCount,
            'items' => $resultData->get()
        ];
    }
    
    //Base repo to get all items
    public function getWebFavorite($skip, $take, $filter, $id){
        $result = QueryBuilder::for($this->table)
                                ->where('is_deleted', 0)
                                ->where('type', 1)
                                ->where('restaurant_id', $id)
                                ->whereHas('lang', function($q){
                                    $q->where('lang_id', Utilities::getLang());
                                })
                                ->allowedFilters($filter);
        $totalCount = $result->get()->count();
        $resultData = $result->where('is_deleted', 0)
                        ->take($take)
                        ->skip($skip)
                        ->orderBy('id', 'desc');
        return [
            'totalCount' => $totalCount,
            'items' => $resultData->get()
        ];
    }

    //Base repo to get item with models by id
    public function getByIdModelWeb($id, $relations,$resturantId){
        return $this->table->where('is_deleted', 0)
        ->where('restaurant_id', $resturantId)
        ->whereHas('lang', function($q){
            $q->where('lang_id', Utilities::getLang());
        })
        ->with($relations)
        ->findOrFail($id);
    }
}