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
}