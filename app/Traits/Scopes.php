<?php 

namespace App\Traits;

trait Scopes 
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFindAll($query)
    {
        return $query->active()->get();
    }

    public function scopeLikeName($query, $search)
    {
        return $query->where('name', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeSlug($query, $search)
    {
        return $query->where('slug', 'like', '%'. $search .'%');
    }

    public function scopeOrderByRelation($query, $relation, $order, $dir) 
    {
        return $query->whereHas($relation, function ($query) use ($relation, $order, $dir) {
            $query->orderBy($order, $dir);
        })->dump();
    }
}