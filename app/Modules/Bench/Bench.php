<?php

namespace App\Modules\Bench;

use App\Modules\Court\Court;
use App\Modules\Cases\Cases;
use App\Models\User;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class Bench extends Model 
{
    use Scopes;

    protected $table = 'bench';

    protected $fillable = [
        'bench_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function court()
    {
        return $this->hasOne(Court::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function cases()
    {
        return $this->hasOne(Cases::class);
    }

    public function scopeLikeBenchName($query, $search)
    {
        return $query->where('bench_name', 'like', '%'. $search .'%');
    }

}