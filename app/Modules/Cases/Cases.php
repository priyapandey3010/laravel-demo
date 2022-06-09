<?php

namespace App\Modules\Cases;

use App\Modules\CaseType\CaseType;
use App\Modules\Bench\Bench;
use App\Modules\Category\Category;
use App\Modules\Status\Status;
use App\Modules\Court\Court;
use App\Modules\Cases\CaseResource;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model 
{
    use Scopes;
    
    protected $table = 'display_board';

    protected $fillable = [
        'case_type',
        'category',
        'item_number',
        'case_number',
        'case_title',
        'status_id',
        'sort_order',
        'is_active',
        'is_display',
        'is_started',
        'is_pass_over',
        'created_date',
        'date_of_hearing',
        'court_id',
        'bench_id',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_display' => 'boolean',
        'is_started' => 'boolean',
        'is_pass_over' => 'boolean'
    ];

    public function setItemNumberAttribute($value)
    {
        $this->attributes['item_number'] = str_pad($value, 4, '0', STR_PAD_LEFT);
    }

    public function scopeLatestSortOrder($query)
    {
        $model = $query->latest()->first();
        return new CaseResource($model);
    }

    public function scopeLikeCaseNumber($query, $search)
    {
        return $query->where('case_number', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeItemNumber($query, $search)
    {
        return $query->orWhere('item_number', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeCaseTitle($query, $search)
    {
        return $query->orWhere('case_title' , 'like', '%'. $search .'%');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function case_type()
    {
        return $this->belongsTo(CaseType::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function bench()
    {
        return $this->belongsTo(Bench::class);
    }

    public function scopeOrLikeCaseType($query, $search)
    {
        return $query->where('case_type', 'like',  '%'. $search .'%');
        // return $query->orWhere(function ($query) use ($search) {
        //     $query->whereHas('case_types', function($query) use ($search) {
        //         $query->where('case_type', 'like',  '%'. $search .'%');
        //     });
        // });
    }

    public function scopeOrLikeCategoryName($query, $search)
    {
        return $query->where('category', 'like',  '%'. $search .'%');
        // return $query->orWhere(function ($query) use ($search) {
        //     $query->whereHas('category', function($query) use ($search) {
        //         $query->where('name', 'like',  '%'. $search .'%');
        //     });
        // });
    }

    public function scopeOrLikeBenchName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('bench', function ($query) use ($search) {
                $query->where('bench_name', 'like', '%'. $search .'%');
            });
        });
    }

    public function scopeOrLikeCourtName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('court', function ($query) use ($search) {
                $query->where('court_name', 'like', '%'. $search .'%');
            });
        });
    }

    public function scopeOrLikeStatus($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('status', function($query) use ($search) {
                $query->where('status', 'like',  '%'. $search .'%');
            });
        });
    }

    public function scopeOldestItemNumber($query)
    {
        return $query->orderBy('item_number', 'asc');
    }

    public function scopeBenchId($query, $id)
    {
        return $query->where('bench_id', $id);
    }

    public function scopeCourtId($query, $id)
    {
        return $query->where('court_id', $id);
    }

    public function scopeNotStatusId($query, $id)
    {
        return $query->where('status_id', '!=', $id);
    }

    public function scopeIsStarted($query, $isStarted)
    {
        return $query->where('is_started', $isStarted);
    }

    public function scopeOrStatusIsNull($query)
    {
        return $query->orWhereNull('status_id');
    }

    public function scopeStatusIsNull($query)
    {
        return $query->whereNull('status_id');
    }

    public function scopeCreatedDate($query, $date)
    {
        return $query->whereDate('created_date', $date);
    }

    public function scopeOldestSortOrder($query)
    {
        return $query->orderBy('sort_order', 'ASC');
    }

    public function scopeBelowSortOrder($query, $sortOrder)
    {
        return $query->where('sort_order', '<', $sortOrder);
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeNotId($query, $id)
    {
        return $query->where('id', '!=', $id);
    }

    public function scopeStatusId($query, $id)
    {
        return $query->where('status_id', $id);
    }

    public function scopeIsDisplay($query, $isDisplay)
    {
        return $query->where('is_display', $isDisplay);
    }

    public function scopeStatusIdIn($query, $statuses)
    {
        return $query->whereIn('status_id', $statuses);
    }
}