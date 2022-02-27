<?php
namespace App\Repository;

use App\Models\Restaurants;
use Spatie\QueryBuilder\QueryBuilder;

class FoodObjectRestaurantsRepository extends BaseRepository {
 
    //Base repo to get all items
    public function getListFood($skip, $take, $relations, $filter, $id){
        $result = QueryBuilder::for($this->table)
                                ->where('food_id', $id)
                                ->where('is_deleted', 0)
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
    //Base repo to get item by id
    public function exists($data){
        return $this->table->where('is_deleted', 0)->where('food_id',$data['food_id'])->where('restaurant_id',$data['restaurant_id'])->first();
    }

    public function addAll($id){
        $resturants = Restaurants::get();
        foreach($resturants as $resturant){
            $data = ['food_id' => $id, 'restaurant_id' => $resturant->id];
            $exists = $this->exists($data);
            if(!$exists){
                $this->table->create($data);
            }
        }
        return 'create successfully';
    }
}