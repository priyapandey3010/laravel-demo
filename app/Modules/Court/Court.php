<?php

namespace App\Modules\Court;

use App\Modules\Bench\Bench;
use App\Modules\Cases\Cases;
use App\Models\User;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class Court extends Model 
{
    use Scopes;
    
    protected $table = 'court';

    protected $fillable = [
        'bench_id',
        'court_number',
        'court_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function bench()
    {
        return $this->belongsTo(Bench::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function cases()
    {
        return $this->hasOne(Cases::class);
    }

    public function scopeLikeBenchId($query, $search)
    {
        return $query->where('bench_id', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeCourtNumber($query, $search)
    {
        return $query->where('court_number', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeCourtName($query, $search)
    {
        return $query->orWhere('court_name', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeBenchName($query, $search)
    {
        return $query->orWhere(function (Builder $query) {
            $query->whereHas('bench', function(Builder $query) use ($search) {
                $query->where('bench_name', 'like',  '%'. $search .'%');
            });
        });
    }
}