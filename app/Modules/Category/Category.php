<?php

namespace App\Modules\Category;

use App\Modules\Cases\Cases;
use App\Modules\Court\Court;
use App\Models\User;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{
    use Scopes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeLikeCategoryName($query, $search)
    {
        return $query->where('name', 'like', '%'. $search .'%');
    }

    public function case()
    {
        return $this->hasOne(Cases::class);
    }

}