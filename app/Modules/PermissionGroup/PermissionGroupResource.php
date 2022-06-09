<?php 

namespace App\Modules\PermissionGroup;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'group_permission_id' => $this->group_permission_id,
            'permission_id' => $this->permission_id,
        ];
    }
}