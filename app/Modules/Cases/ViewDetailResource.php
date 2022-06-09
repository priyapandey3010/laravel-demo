<?php 

namespace App\Modules\Cases;

use App\Modules\Status\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $statusPassOver = Status::status('Pass Over')->first();
        $statusYetToBeTaken = Status::status('Yet to be Taken')->first();

        return [
            'id' => $this->id,
            'case_type' => $this->case_type,
            'category_name' => $this->category,
            //'category_id' => $this->category_id,
            //'case_type' => $this->case_type->case_type ?? '',
            'item_number' => $this->item_number,
            'case_number' => $this->case_number,
            'case_title' => $this->case_title,
            'status_id' => $this->status_id ?? $statusYetToBeTaken->id,
            'status' => $this->status_id ? $this->status->status : $statusYetToBeTaken->status,
            'colour_code' => $this->status_id ? $this->status->colour_code : $statusYetToBeTaken->colour_code,
            'sort_order' => $this->sort_order,	
            'is_active' => $this->is_active,
            'case_upload_id' => $this->case_upload_id,
            'court_id' => $this->court_id,
            'court_number' => $this->court_id ? $this->court->court_number : '',
            'court_name' => $this->court_id ? $this->court->court_name : '',
            'bench_name' => $this->bench_id ? ($this->bench->bench_name ?? '') : '',
            'is_display' => $this->is_display,
            'is_pass_over' => $this->is_pass_over,
            'pass_over_id' => $statusPassOver->id,
            'pass_over_status' => $statusPassOver->status,
            'pass_over_color' => $statusPassOver->colour_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}