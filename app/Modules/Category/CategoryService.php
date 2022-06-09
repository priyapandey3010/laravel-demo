<?php declare (strict_types = 1);

namespace App\Modules\Category;

use App\Core\BaseService;
use App\Modules\Category\Category as Model;
use App\Modules\Category\CategoryResource as Resource;

class CategoryService extends BaseService 
{
    protected static $model = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'name',
    ];

    public function getDataTableList()
    {
        [
            'search' => $search,
            'order' => $order,
            'limit' => $limit,
            'dir' => $dir,
            'page' => $page
        ] = $this->getDataTableParams();

        $query = Model::query();

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) use ($search) {
                $query
                    ->likeCategoryName($search);
            });
        }

        $query->orderBy($order, $dir);

        if (isset($page) && !empty($page)) {
            return $this->getDataTableResult(
                Resource::collection(
                    $query->paginate($limit)
                )
            );
        }

        return Resource::collection($query->get());
    }

}