<?php 

namespace App\Modules\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray($request)
    { print_r($request); die('dd');
        return [ 
            'case_number' => $this->court,
            'case_type' => $this->bench,
            'is_active' => $this->court,
        ];
    }
}