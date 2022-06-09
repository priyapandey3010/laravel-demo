<?php 

namespace App\Modules\DisplayBoardUploads;

use Illuminate\Http\Resources\Json\JsonResource;

class DisplayBoardUploadsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'original_file_name' => $this->original_file_name,
            'upload_date' => $this->upload_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}