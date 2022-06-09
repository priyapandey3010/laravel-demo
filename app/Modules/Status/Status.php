<?php

namespace App\Modules\Status;

use App\Modules\Cases\Cases;
use App\Modules\Status\StatusResource;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class Status extends Model 
{
    use Scopes;
    
    protected $table = 'status';

    protected $fillable = [
        'status',
        'colour_code',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    public function case()
    {
        return $this->hasOne(Cases::class);
    }

    public function scopeLikeStatusName($query, $search)
    {
        return $query->where('status', 'like', '%'. $search .'%');
    }

    public function scopeDefaults($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeClearDefaults($query)
    {
        return $query
            ->defaults()
            ->update([
                'is_default' => false
            ]);
    }

    public function scopeFetchDefaultStatus($query)
    {
        $model = $query->defaults()->first();
        return StatusResource::make($model)->resolve();
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}