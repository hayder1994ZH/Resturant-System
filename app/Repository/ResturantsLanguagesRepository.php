<?php
namespace App\Repository;

class ResturantsLanguagesRepository extends BaseRepository {
     //Base repo to get item with models by id
     public function getByRestaurantId($id, $relations){
        return $this->table->where('restaurant_id', $id)->where('is_deleted', 0)->with($relations)->get();
    }
}