<?php
namespace App\Repository;

use App\Helpers\Utilities;
use Spatie\QueryBuilder\QueryBuilder;

class SlidersRepository extends BaseRepository {
    
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

    //======= web repo 
    
    //Base repo to get all items
    public function getWeb($skip, $take, $filter, $id){
        $result = QueryBuilder::for($this->table)
                                ->where('is_deleted', 0)
                                ->where('restaurant_id', $id)
                                ->whereHas('meal.lang', function($q){
                                    $q->where('lang_id', Utilities::getLang());
                                })
                                ->with('meal.lang')
                                ->allowedFilters($filter);
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