<?php
namespace App\Repository;

use App\Helpers\Utilities;
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
        return $result->orderBy('id', 'desc')->get();
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

    //Base repo to get item with models by id
    public function getByIdModelWeb($id,$resturantId){
        return $this->table->where('is_deleted', 0)
        ->where('restaurant_id', $resturantId)
        ->whereHas('lang', function($q){
            $q->where('lang_id', Utilities::getLang());
        })
        ->findOrFail($id);
    }
}