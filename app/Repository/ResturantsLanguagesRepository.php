<?php
namespace App\Repository;

use App\Helpers\Utilities;

class ResturantsLanguagesRepository extends BaseRepository {
    //Base repo to get item with models by id
    public function getByRestaurantId($id, $relations){
       return $this->table->where('restaurant_id', $id)->where('is_deleted', 0)->with($relations)->get();
   }

   //Base repo to get item with models by id
   public function getByRestaurantUid($uid, $relations){
    $restaurant = Utilities::getRestaurant($uid);
    return $this->table->where('restaurant_id', $restaurant->id)
                        ->where('is_deleted', 0)
                        ->with($relations)->get()
                        ->map(function ($item) {
                            $data['id'] = $item->lang->id;
                            $data['name'] = $item->lang->name;
                            return $data;
                        });
  }
}