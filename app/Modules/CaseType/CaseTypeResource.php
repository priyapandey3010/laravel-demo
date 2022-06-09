<?php 

namespace App\Modules\CaseType;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'case_type' => $this->case_type,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}