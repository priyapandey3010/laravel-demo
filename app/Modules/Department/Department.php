<?php

namespace App\Modules\Department;

use App\Traits\Scopes;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Department extends Model 
{
    use Scopes;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function scopeLikeDepartmentName($query, $search)
    {
        return $query->where('name', 'like', '%'. $search .'%');
    }
}