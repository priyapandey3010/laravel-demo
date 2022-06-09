<?php

namespace App\Models;

use App\Models\PasswordHistory;
use App\Traits\Scopes;
use App\Modules\Bench\Bench;
use App\Modules\Court\Court;
use App\Modules\Role\Role;
use App\Modules\Department\Department;
use App\Modules\Designation\Designation;
use App\Modules\UserPermission\UserPermission;
use App\Modules\Permission\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Scopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'designation_id',
        'department_id',
        'role_id',
        'category_type',
        'profile_picture',
        'contact_number',
        'bench_id',
        'court_id',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault();
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class)->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function bench()
    {
        return $this->belongsTo(Bench::class)->withDefault();
    }

    public function court()
    {
        return $this->belongsTo(Court::class)->withDefault();
    }

    public function scopeLikeFullName($query, $search)
    {
        return $query->whereRaw("CONCAT_WS(' ',first_name,last_name) like '%?%'", [$search]);
    }

    public function scopeOrLikeEmail($query, $search)
    {
        return $query->orWhere('email', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeUserName($query, $search)
    {
        return $query->orWhere('username', 'like', '%'. $search .'%');
    }

    public function scopeOrLikeRoleName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('role', function ($query) use ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            });
        });
    }

    public function scopeOrLikeDepartmentName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('department', function ($query) use ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            });
        });
    }

    public function scopeOrLikeDesignationName($query, $search)
    {
        return $query->orWhere(function ($query) use ($search) {
            $query->whereHas('designation', function ($query) use ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            });
        });
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

    public function scopeOrLikeCategoryType($query, $search)
    {
        return $query->orWhereRaw("IF(category_type='?', '?', '?') like '%?%'", [
            config('category.display_board'),
            __('message.display_board'),
            __('message.website'),
            $search
        ]);
    }

    public function scopeWhereRoleNotIn($query, $role)
    {
        return $query->where(function ($query) use ($role) {
            $query->whereHas('role', function ($query) use ($role) {
                return $query->whereNotIn('slug', [$role]);
            });
        });
    }

    public function roles()
    {
        return $this->hasOne(Role::class);
    }

    public function scopeUserPermissions($query, $userId)
    {
        return $query->select('permissions.slug')
                ->join('user_permissions', 'users.id', '=', 'user_permissions.user_id')
                ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                ->where('user_permissions.user_id', $userId)
                ->get();
    }
}
