<?php
namespace App\Repository;

use App\Models\LangBodys;
use Spatie\QueryBuilder\QueryBuilder;

class CategoriesRepository extends BaseRepository {
 
    //Base repo to get all category languges
    public function getCategoryLang($id, $relations){
        $result = QueryBuilder::for(LangBodys::class)
                                ->where('is_deleted', 0)
                                ->where('tbable_id', $id)
                                ->where('tbable_type', 'Categories');
                                if(!empty($relations)){
                                    $result = $result->with($relations);
                                }
        return $result->orderBy('created_at', 'desc')->get();
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