<?php declare (strict_types = 1);

namespace App\Modules\RolePermission;

use App\Core\BaseService;
use App\Models\User;
use App\Modules\UserPermission\UserPermission;
use App\Modules\PermissionGroup\PermissionGroup;
use App\Modules\PermissionGroup\PermissionGroupResource;
use App\Modules\RolePermission\RolePermission as Model;
use App\Modules\RolePermission\RolePermissionResource as Resource;
use Illuminate\Support\Facades\DB;

class RolePermissionService extends BaseService 
{
    protected static $model = Model::class;
    protected static $resource  = Resource::class;

    public function create($payload)
    {
        return DB::transaction(function () use ($payload) {
            
            $roleId = $payload['role_id'];
            $permissions  = $payload['permissions'];
            $permissionGroups = $this->getPermissionGroups($permissions);
            $rolePermissions = $this->getMappedPermissions($permissions, $roleId, $permissionGroups); 
                   
            if ($rolePermissions) {
                Model::whereRoleId($roleId)->delete();
                Model::insert($rolePermissions);
                $users = User::select('id')->where('role_id', $roleId)->get();
                if ($users) {
                    foreach ($users as $user) {
                        $userPermissions = $this->getMappedUserPermissions($permissions, $user->id, $permissionGroups);
                        UserPermission::where('user_id', $user->id)->delete();
                        UserPermission::insert($userPermissions); 
                    }
                    
                }
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

    protected function getMappedPermissions($permissions, $roleId, $permissionGroups)
    {
        return array_map(function ($permission) use ($roleId, $permissionGroups) {
            return [
                'role_id' => $roleId,
                'permission_id' => $permission,
                'group_permission_id' => $this->getFilteredGroupPermissionId($permission, $permissionGroups)
            ];
        }, $permissions);
    }

    protected function getMappedUserPermissions($permissions, $userId, $permissionGroups)
    {
        return array_map(function ($permission) use ($userId, $permissionGroups) {
            return [
                'user_id' => $userId,
                'permission_id' => $permission,
                'is_default' => true,
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
        $model = Model::whereRoleId($id)->get();
        $collection = Resource::collection($model)->resolve();
        return array_column($collection, 'permission_id');
    }
}