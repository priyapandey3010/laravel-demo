<?php 

namespace App\Modules\ManageCaseMaster;

use Illuminate\Http\Resources\Json\JsonResource;

class ManageCaseMasterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'case_type_id' => $this->case_type_id,
            'case_type' => $this->case_type->case_type ?? '',
            'case_number' => $this->case_number,
            'case_title' => $this->case_title,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}