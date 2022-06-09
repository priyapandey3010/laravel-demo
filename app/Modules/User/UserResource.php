<?php 

namespace App\Modules\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name, 
            'department_id' => $this->department_id,
            'department_name' => $this->department->name, 
            'designation_id' => $this->designation_id, 
            'designation_name' => $this->designation->name, 
            'category_type_id' => $this->category_type,
            'category_type' => $this->category_type === config('category.display_board') ? __('message.display_board') : __('message.website'), 
            'bench_id' => $this->bench_id, 
            'bench_name' => $this->bench->bench_name, 
            'court_id' => $this->court_id, 
            'court_name' => $this->court->court_name, 
            'contact_number' => $this->contact_number,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}