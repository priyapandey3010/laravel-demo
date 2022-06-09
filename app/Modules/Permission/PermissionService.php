<?php declare (strict_types = 1);

namespace App\Modules\Permission;

use App\Core\BaseService;
use App\Modules\Permission\Permission as Model;
use App\Modules\Permission\PermissionResource as Resource;

class PermissionService extends BaseService 
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
                    ->likeName($search)
                    ->orLikeSlug($search);
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

    public function create($payload)
    {
        $this->setPayload($payload, 'slug', 'name');
        parent::create($payload);
    }

    public function update($payload, $id)
    {
        $this->setPayload($payload, 'slug', 'name');
        parent::update($payload, $id);
    }

}