<?php declare (strict_types = 1);

namespace App\Modules\Court;

use App\Core\BaseService;
use App\Modules\Court\Court as Model;
use App\Modules\Court\CourtResource as Resource;

class CourtService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'bench_id',
        2 => 'court_number',
        3 => 'court_name',
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

        $query = Model::with('bench');

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) {
                $query
                    ->likeBenchId($search)
                    ->orLikeBenchName($search)
                    ->orLikeCourtNumber($search)
                    ->orLikeCourtName($search);
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