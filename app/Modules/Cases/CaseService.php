<?php declare (strict_types = 1);

namespace App\Modules\Cases;

use App\Core\BaseService;
use App\Modules\Cases\Cases as Model;
use App\Modules\Status\Status;
use App\Modules\Court\Court;
use App\Modules\Cases\CaseResource as Resource;
use Illuminate\Support\Facades\DB;


class CaseService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'case_type_id',
        2 => 'category_id',
        3 => 'case_title',
        4 => 'item_number',
        5 => 'case_number',
        6 => 'status_id',
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

        $query = Model::with(['status','case_type', 'court', 'bench']);

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) use ($search) {
                $query
                    ->likeCaseNumber($search)
                    ->orLikeStatus($search)
                    ->orLikeItemNumber($search)
                    ->orLikeCaseTitle($search);
            });
        }

        $query->where('created_by', auth()->user()->id);
        $query->orderBy('sort_order', 'ASC');

        if (isset($page) && !empty($page)) {
            return $this->getDataTableResult(
                Resource::collection(
                    $query->paginate($limit)
                )
            );
        }

        return Resource::collection($query->get());
    }

    public function nextSortOrder()
    {
        $model = Model::latestSortOrder();    
        return $model ? ((int) $model['sort_order']) + 1 : 1;
    }

    public function create($payload)
    {
        $payload['sort_order'] = $this->nextSortOrder();
        $payload['created_date'] = date('Y-m-d');

        return parent::create($payload);
    }

    public function reorder($payload)
    {
        $reorders = $payload['reorder'];
        
        return DB::transaction(function() use($reorders) {
            foreach ($reorders as $reorder) {
                Model::where(['id' => $reorder['id']])
                  ->update(['sort_order' => $reorder['sort_order']]);
            }
        });
    }

    public function getCourts($id)
    {
        return Court::where('bench_id', $id)->get()->toArray();
    }
}