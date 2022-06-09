<?php declare (strict_types = 1);

namespace App\Modules\UserPermission;

use App\Core\BaseService;
use App\Modules\PermissionGroup\PermissionGroup;
use App\Modules\PermissionGroup\PermissionGroupResource;
use App\Modules\UserPermission\UserPermission as Model;
use App\Modules\UserPermission\UserPermissionResource as Resource;
use Illuminate\Support\Facades\DB;

class UserPermissionService extends BaseService 
{
    protected static $model = Model::class;
    protected static $resource  = Resource::class;

    public function create($payload)
    {
        return DB::transaction(function () use ($payload) {
            
            $userId = $payload['user_id'];
            $permissions  = $payload['permissions'];
            $permissionGroups = $this->getPermissionGroups($permissions);
            $userPermissions = $this->getMappedPermissions($permissions, $userId, $permissionGroups); 
                   
            if ($userPermissions) {
                Model::whereUserId($userId)->delete();
                Model::insert($userPermissions);
            }
            
            if ($userId == auth()->user()->id) {
                set_auth_permissions();
            }
        });
    }

    protected function getFilteredGroupPermissionId($permissionId, $permissionGroups) 
    {
        $result = array_filter($permissionGroups, function($permissionGroup) use ($permissionId) {
            return intval($permissionGroup['permission_id']) === intval($permissionId);
        });
        
        $result = array_values($result);

        return ($result && isset($result[0])) ? $result[0]['group_permission_id'] : null;
    }

    protected function getMappedPermissions($permissions, $userId, $permissionGroups)
    {
        return array_map(function ($permission) use ($userId, $permissionGroups) {
            return [
                'user_id' => $userId,
                'permission_id' => $permission,
                'is_default' => false,
                'group_permission_id' => $this->getFilteredGroupPermissionId($permission, $permissionGroups)
            ];
        }, $permissions);
    }

    public function getPermissionGroups($permissions)
    {
        $model = PermissionGroup::whereIn('permission_id', $permissions)->get();
        return PermissionGroupResource::collection($model)->resolve();
    }

    public function findById($id)
    {
        $model = Model::whereUserId($id)->get();
        $collection = Resource::collection($model)->resolve();
        return array_column($collection, 'permission_id');
    }
}