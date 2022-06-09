<?php

namespace App\Modules\DisplayBoardUploads;

use Illuminate\Database\Eloquent\Model;

class DisplayBoardUploads extends Model 
{
    protected $table = 'display_board_uploads';

    protected $fillable = [
        'file_name',
        'original_file_name',
        'upload_date',
        'is_submitted',
        'created_by'
    ];

    protected $casts = [
        'is_submitted' => 'boolean'
    ];

    public function scopeSubmitted($query)
    {
        return $query->where('is_submitted', true);
    }

    public function scopeLikeFileName($query, $search)
    {
        return $query->orWhere('file_name', 'like', '%'. $search .'%');
    }

    public function scopeLikeOriginalFileName($query, $search)
    {
        return $query->orWhere('original_file_name', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeUploadDate($query, $search)
    {
        return $query->orWhere('upload_date', 'like', '%'. $search .'%');
    }
}