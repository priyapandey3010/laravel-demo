<?php 

namespace App\Modules\DisplayBoardUploads;

use App\Modules\DisplayBoardUploads\DisplayBoardUploads;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class DisplayBoardUploadsImport implements ToModel
{
    public function model(array $row)
    {
        dd($row);
    }
}