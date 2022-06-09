<?php declare (strict_types = 1);

namespace App\Modules\User;

use App\Core\BaseService;
use App\Models\User as Model;
use App\Models\PasswordHistory;
use App\Modules\User\UserResource as Resource;
use App\Modules\RolePermission\RolePermission;
use App\Modules\UserPermission\UserPermission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'first_name',
        2 => 'username',
        3 => 'email',
        4 => 'roles.name',
        5 => 'departments.name',
        6 => 'designations.name',
        7 => 'bench.bench_name',
        8 => 'court.court_name',

    ];

    public function getDataTableList()
    {
        [
            'search' => $search,
            'order' => $order,
            'limit' => $limit,
            'dir' => $dir,
            'page' => $page
        ] = $this->getDataTableParams();

        $query = Model::query();

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) use ($search) {
                $query->likeFullName($search)
                    ->orLikeUserName($search)
                    ->orLikeEmail($search)
                    //->orLikeCategoryType($search)
                    ->orLikeRoleName($search)
                    ->orLikeDepartmentName($search)
                    ->orLikeDesignationName($search)
                    ->orLikeBenchName($search)
                    ->orLikeCourtName($search);
            });
        }

        //$query->whereRoleNotIn('super-admin');

        $query->orderBy($order, $dir);

        if (isset($page) && !empty($page)) {
            return $this->getDataTableResult(
                Resource::collection(
                    $query->paginate($limit)
                )
            );
        }

        return Resource::collection($query->get());
    }

    public function uploadPhoto($field, $uploadPath)
    {
        if (request()->file()) 
        {
            $originalFileName = request()->{$field}->getClientOriginalName();
            $sysFileName = time() . '_' . $originalFileName;
            $filePath = request()->file($field)->storeAs($uploadPath, $sysFileName);
            
            return [
                'system_file_name' => $sysFileName,
                'original_file_name' => $originalFileName,
                'upload_file_path' => $filePath
            ];
        }
    }

    public function createUserDefaultPermissions($userId, $payload)
    {
        $roleId = $payload['role_id'];
        $rolePermissions = RolePermission::whereRoleId($roleId)->get();
        $userPermissions = [];
        if ($rolePermissions) 
        {
            foreach ($rolePermissions as $rolePermission) 
            {
                $userPermissions[] = [
                    'user_id' => $userId,
                    'permission_id' => $rolePermission->permission_id,
                    'is_default' => true,
                    'group_permission_id' => $rolePermission->group_permission_id
                ];
            }
            
            if ($userPermissions) 
            {
                return UserPermission::insert($userPermissions);
            }
        }
    }

    public function deleteUserDefaultPermissions($userId)
    {
        return UserPermission::userId($userId)->delete();
    }

    public function create($payload)
    {
        $uploadPhoto = $this->uploadPhoto('image', config('upload.profile_photo_path'));
        return DB::transaction(function() use($payload, $uploadPhoto) {
            $payload['profile_picture'] = $uploadPhoto['system_file_name'];
            if ($payload['category_type'] == config('category.display_board')) {
                if ($payload['role_id'] == config('role.bench-admin')) {
                    $payload['court_id'] = null;
                }
                
                if ($payload['role_id'] == config('role.super-admin')) {
                    $payload['bench_id'] = null;
                    $payload['court_id'] = null;
                }
            }
            $user = Model::create($payload);
            if ($user) {
               $this->createUserDefaultPermissions($user->id, $payload);         
               PasswordHistory::create([
                   'user_id' => $user->id,
                   'password' => $user->password
               ]);
            }
        });
    }

    public function update($payload, $id)
    {
        return DB::transaction(function() use($payload, $id) {
            
            if (intval($payload['category_type']) === config('category.website')) {
                $payload['bench_id'] = null;
                $payload['court_id'] = null;
            }

            if ($payload['category_type'] == config('category.display_board')) {
                if ($payload['role_id'] == config('role.bench-admin')) {
                    $payload['court_id'] = null;
                }
                
                if ($payload['role_id'] == config('role.super-admin')) {
                    $payload['bench_id'] = null;
                    $payload['court_id'] = null;
                }
            }
            
            $user = Model::findOrFail($id);
            $user->fill($payload)->save();
            if ($user) {
                $this->deleteUserDefaultPermissions($user->id);
                $this->createUserDefaultPermissions($user->id, $payload);         
            }
        });
    }

    public function updateById($payload, $id, $hasPassword = false)
    {
        return DB::transaction(function() use($payload, $id, $hasPassword) {
            $user = Model::findOrFail($id);
            $user->fill($payload)->save();
            if ($hasPassword) {
                PasswordHistory::create([
                    'user_id' => $user->id,
                    'password' => $user->password
                ]);
            }
            //\Mail::to('kunalbaniya@uneecops.com')->send(new \App\Mail\PasswordChangedMail());
        });
    }

    public function updatePhoto($payload, $id)
    {
        $user = Model::findOrFail($id);
        unlink(storage_path('app/uploads/photos/'. $user->profile_picture));
        // if (Storage::disk('local')->exists(config('upload.profile_photo_path').'/' . $user->profile_picture)) 
        //     Storage::delete(config('upload.profile_photo_path').'/' . $user->profile_picture);
        $uploadPhoto = $this->uploadPhoto('image', config('upload.profile_photo_path'));
        $payload['profile_picture'] = $uploadPhoto['system_file_name'];
        return $user->fill($payload)->save();
    }
}