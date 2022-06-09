<?php

namespace App\Modules\Designation;

use App\Traits\Scopes;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model 
{
    use Scopes;

    protected $table = 'designations';

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

    public function scopeLikeDesignationName($query, $search)
    {
        return $query->where('name', 'like', '%'. $search .'%');
    }
}