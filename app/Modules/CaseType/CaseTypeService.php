<?php declare (strict_types = 1);

namespace App\Modules\CaseType;

use App\Core\BaseService;
use App\Modules\CaseType\CaseType as Model;
use App\Modules\CaseType\CaseTypeResource as Resource;

class CaseTypeService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'case_type',
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
                $query->likeCaseType($search);
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