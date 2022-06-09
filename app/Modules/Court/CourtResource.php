<?php 

namespace App\Modules\Court;

use Illuminate\Http\Resources\Json\JsonResource;

class CourtResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bench_id' => $this->bench_id,
            'bench_name' => $this->bench->bench_name ?? '',
            'court_number' => $this->court_number,
            'court_name' => $this->court_name,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}