<?php 

declare (strict_types = 1);

namespace App\Modules\DisplayBoardUploads;

use App\Core\BaseValidation;
use App\Rules\FileName;
use Illuminate\Validation\Rule;

class DisplayBoardUploadsValidation extends BaseValidation 
{
    protected static function rules(?int $id = null): array
    {
        return [
            'file' => [
                'bail',
                'required',
                'mimes:xlsx',
                'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'max:2048',
                'unique:display_board_uploads,file_name',
                new FileName(),
            ],
            'upload_date' => [
                'bail',
                'required',
                'date',
                'after_or_equal:today'
            ]
        ];
    }
}