<?php declare (strict_types = 1);

namespace App\Modules\ManageCaseMaster;

use App\Core\BaseService;
use App\Modules\ManageCaseMaster\ManageCaseMaster as Model;
use App\Modules\Status\Status;
use App\Modules\Court\Court;
use App\Modules\ManageCaseMaster\ManageCaseMasterResource as Resource;
use Illuminate\Support\Facades\DB;


class ManageCaseMasterService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'case_type_id',
        2 => 'category_id',
        3 => 'case_title',
        4 => 'case_number',
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

        $query = Model::with(['case_type']);

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) use ($search) {
                $query
                    ->likeCaseNumber($search)
                    ->orLikeCaseType($search)
                    ->orLikeCaseTitle($search);
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