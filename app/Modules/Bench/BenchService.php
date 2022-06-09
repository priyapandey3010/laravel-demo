<?php declare (strict_types = 1);

namespace App\Modules\Bench;

use App\Core\BaseService;
use App\Modules\Bench\Bench as Model;
use App\Modules\Bench\BenchResource as Resource;

class BenchService extends BaseService 
{
    protected static $model = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'bench_name',
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
                    ->likeBenchName($search);
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