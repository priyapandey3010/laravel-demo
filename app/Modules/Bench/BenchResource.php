<?php 

namespace App\Modules\Bench;

use Illuminate\Http\Resources\Json\JsonResource;

class BenchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bench_name' => $this->bench_name,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}