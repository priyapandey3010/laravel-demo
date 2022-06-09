<?php declare (strict_types = 1);

namespace App\Modules\AuditTrail;

use App\Core\BaseService;
use App\Modules\AuditTrail\AuditTrail as Model; 
use App\Modules\AuditTrail\AuditTrailResource as Resource; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuditTrailService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'module_name',
        2 => 'activity_type',
        3 => 'user_name',
        4 => 'email_id',
        5 => 'role_id',
        6 => 'last_access',
        7 => 'last_login',
        8 => 'activity_date_time',
        9 => 'logout_time',
        10 => 'ip_address',

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
                $query->likeUserName($search)
                    ->orLikeEmail($search)
                    ->orLikeRoleName($search);
            });
        }
 

        $query->orderBy($order, $dir);
       // echo $query->toSql(); die();
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