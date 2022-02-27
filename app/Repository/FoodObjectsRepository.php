<?php
namespace App\Repository;

use Spatie\QueryBuilder\QueryBuilder;

class FoodObjectsRepository extends BaseRepository {
 

    //======= web repo 
    
    //Base repo to get all items
    public function getWeb($skip, $take, $id){
        $result = QueryBuilder::for($this->table)
                                ->where('is_deleted', 0)
                                ->where('restaurant_id', $id);
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
}