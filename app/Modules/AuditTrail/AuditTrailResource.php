<?php 

namespace App\Modules\AuditTrail;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditTrailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'module_name' => $this->module_name,
            'activity_type' => $this->activity_type,
            'user_name' => $this->user_name,
            'email_id' => $this->email_id, 
            'role_id' => $this->role->name,
            //'role_name' => $this->role->name, 
            'last_access' => $this->last_access,
            'last_login' => $this->last_login, 
            'activity_datetime' => $this->activity_datetime, 
            'logout_time' => $this->logout_time,
            'ip_address' => $this->ip_address, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}