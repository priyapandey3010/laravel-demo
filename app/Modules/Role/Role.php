<?php

namespace App\Modules\Role;

use App\Traits\Scopes;
use App\Models\User;
use App\Traits\Mutators;
use Illuminate\Database\Eloquent\Model;

class Role extends Model 
{
    use Scopes, Mutators;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}