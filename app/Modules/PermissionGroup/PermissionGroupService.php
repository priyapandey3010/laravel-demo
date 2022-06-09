<?php declare (strict_types = 1);

namespace App\Modules\PermissionGroup;

use App\Core\BaseService;
use App\Modules\PermissionGroup\PermissionGroup as Model;
use App\Modules\PermissionGroup\PermissionGroupResource as Resource;
use Illuminate\Support\Facades\DB;

class PermissionGroupService extends BaseService 
{
    protected static $model = Model::class;
    protected static $resource  = Resource::class;

    public function create($payload)
    {
        return DB::transaction(function () use ($payload) {
            
            $permissionId = $payload['permission_id'];
            $permissions  = $payload['permissions'];
            $permissions  = $this->getFilteredPermissions($permissions, $permissionId);
            $permissionGroups = $this->getMappedPermissions($permissions, $permissionId);
                        
            if ($permissionGroups) {
                Model::whereGroupPermissionId($permissionId)->delete();
                Model::insert($permissionGroups);
            } 
        });
    }

    protected function getFilteredPermissions($permissions, $permissionId) 
    {
        return array_filter($permissions, function($permission) use ($permissionId) {
            return $permission !== $permissionId;
        });
    }

    protected function getMappedPermissions($permissions, $permissionId)
    {
        return array_map(function ($permission) use ($permissionId) {
            return [
                'group_permission_id' => $permissionId,
                'permission_id' => $permission
            ];
        }, $permissions);
    }

    public function findById($id)
    {
        $model = Model::whereGroupPermissionId($id)->get();
        $collection = Resource::collection($model)->resolve();
        return array_column($collection, 'permission_id');
    }
}