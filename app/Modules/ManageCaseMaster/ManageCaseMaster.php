<?php

namespace App\Modules\ManageCaseMaster;

use App\Modules\CaseType\CaseType;
use App\Modules\ManageCaseMaster\ManageCaseMasterResource;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class ManageCaseMaster extends Model 
{
    use Scopes;
    
    protected $table = 'manage_case_master';

    protected $fillable = [
        'case_type_id',
        'category_id',
        'case_number',
        'case_title',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function scopeLikeCaseNumber($query, $search)
    {
        return $query->where('case_number', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeCaseTitle($query, $search)
    {
        return $query->orWhere('case_title' , 'like', '%'. $search .'%');
    }

    public function scopeOrLikeCaseType($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('case_types', function($query) use ($search) {
                $query->where('case_type', 'like',  '%'. $search .'%');
            });
        });
    }

    public function case_type()
    {
        return $this->belongsTo(CaseType::class);
    }

    public function scopeOrLikeCategoryName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('category', function($query) use ($search) {
                $query->where('name', 'like',  '%'. $search .'%');
            });
        });
    }
}