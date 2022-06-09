<?php 

namespace App\Modules\RolePermission;

use Illuminate\Http\Resources\Json\JsonResource;

class RolePermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'role_id' => $this->role_id,
            'permission_id' => $this->permission_id,
            'group_permission_id' => $this->group_permission_id,
        ];
    }
}