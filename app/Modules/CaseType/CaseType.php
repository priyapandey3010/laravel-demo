<?php

namespace App\Modules\CaseType;

use App\Modules\Cases\Cases;
use App\Modules\ManageCaseMaster\ManageCaseMaster;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class CaseType extends Model 
{
    use Scopes;
    
    protected $table = 'case_types';

    protected $fillable = [
        'case_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function case()
    {
        return $this->hasOne(ManageCaseMaster::class);
    }

    public function scopeLikeCaseType($query, $search)
    {
        return $query->orWhere('case_type', 'like', '%'. $search .'%');
    }
}