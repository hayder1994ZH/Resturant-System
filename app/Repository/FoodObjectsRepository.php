<?php
namespace App\Repository;

use App\Models\FoodObjectRestaurants;
use Spatie\QueryBuilder\QueryBuilder;

class FoodObjectsRepository extends BaseRepository {
 

    //======= web repo 
    
    //Base repo to get all items
    public function getWeb($skip, $take, $id){
        $result = FoodObjectRestaurants::where('is_deleted', 0)
                                ->with('food_order')
                                ->where('restaurant_id', $id);
        $totalCount = $result->get()->count();
        $resultData = $result->take($take)
                                ->skip($skip)
                                ->orderBy('id', 'desc');
        return [
            'totalCount' => $totalCount,
            'items' => $resultData->get()->map(function ($item) {
                $data['id'] = $item->food_order->id;
                $data['name'] = $item->food_order->name;
                $data['ios_url'] = $item->food_order->ios_url;
                $data['android_url'] = $item->food_order->android_url;
                $data['logo'] = $item->food_order->logo;
                $data['created_at'] = $item->food_order->created_at;
                $data['updated_at'] = $item->food_order->updated_at;
                return $data;
            })
        ];
    }
}