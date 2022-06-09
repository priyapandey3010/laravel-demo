<?php 

use App\Modules\Bench\Bench;
use App\Modules\CaseType\CaseType;
use App\Modules\Status\Status;
use App\Modules\Permission\Permission;
use App\Modules\Designation\Designation;
use App\Modules\Department\Department;
use App\Modules\Category\Category;
use App\Modules\Role\Role;
use App\Modules\Court\Court;
use App\Modules\ManageCaseMaster\ManageCaseMaster;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

if (! function_exists('make_list')) {
    function make_list($result, $key, $value, $withEmpty = true) {
        
        $list = [];
        
        if ($withEmpty)
            $list[''] = __('message.select');
        
        foreach ($result as $row) {
            $list[$row->{$key}] = $row->{$value};
        }
        
        return $list;
    }
}

if (! function_exists('bench_list')) {
    function bench_list() {
        $benches = Bench::findAll();
        return $benches
         ? make_list($benches, 'id', 'bench_name')
         : [];
    }
}

if (! function_exists('case_type_list')) {
    function case_type_list() {
        $caseTypes = CaseType::findAll();
        return $caseTypes
         ? make_list($caseTypes, 'id', 'case_type')
         : [];
    }
}

if (! function_exists('status_list')) {
    function status_list() {
        $statuses = Status::findAll();
        return $statuses
         ? make_list($statuses, 'id', 'status')
         : [];
    }
}

if (! function_exists('permission_list')) {
    function permission_list() {
        $permissions = Permission::findAll();
        return $permissions
         ? make_list($permissions, 'id', 'name', false)
         : [];
    }
}

if (! function_exists('designation_list')) {
    function designation_list() {
        $designations = Designation::findAll();
        return $designations
         ? make_list($designations, 'id', 'name')
         : [];
    }
}

if (! function_exists('department_list')) {
    function department_list() {
        $departments = Department::findAll();
        return $departments
         ? make_list($departments, 'id', 'name')
         : [];
    }
}

if (! function_exists('category_list')) {
    function category_list() {
        $categories = Category::findAll();
        return $categories
         ? make_list($categories, 'id', 'name')
         : [];
    }
}

if (! function_exists('category_type_list')) {
    function category_type_list() {
        return [
            '' => __('message.select'),
            config('category.website') => __('message.website'),
            config('category.display_board') => __('message.display_board')
        ];
    }
}

if (! function_exists('role_list')) {
    function role_list() {
        $roles = Role::findAll();
        return $roles
         ? make_list($roles, 'id', 'name')
         : [];
    }
}

if (! function_exists('court_list')) {
    function court_list() {
        $courts = Court::findAll();
        return $courts
         ? make_list($courts, 'id', 'court_name')
         : [];
    }
}

if (! function_exists('bench_court_list')) {
    function bench_court_list() {
        $benchId = auth()->user()->bench_id;
        $courts = Court::where('bench_id', $benchId)->get();
        return $courts
         ? make_list($courts, 'id', 'court_name')
         : [];
    }
}

if (! function_exists('random_key')) {
    function random_key() {
        return bin2hex(random_bytes(16));
    }
}

if (! function_exists('crypto_secrets')) {
    function crypto_secrets() {
        session([
            'crypto_key' => random_key(),
            'crypto_salt' => random_key(),
            'crypto_iv' => random_key(),
            'crypto_key_size' => 64/8,
            'crypto_iterations' => 999
        ]);
    }
}

if (! function_exists('crypto_decrypt')) {
    function crypto_decrypt($encrypt) {
        if (! empty($encrypt)) {
            $encrypt = base64_decode($encrypt);
            $iterations = session('crypto_iterations');
            $salt = hex2bin(session('crypto_salt'));
            $iv = hex2bin(session('crypto_iv'));
            $key = hash_pbkdf2("sha512", session('crypto_key'), $salt, $iterations, 64);
            
            return openssl_decrypt(
                $encrypt, 
                'AES-256-CBC', 
                hex2bin($key), 
                OPENSSL_RAW_DATA, 
                $iv
            );
        }
    }
}

if (! function_exists('profile_image')) {
    function profile_image() {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        return asset('photos/'. $user->profile_picture);
    }
}

if (! function_exists('set_auth_permissions')) {
    function set_auth_permissions() {
        $userId = auth()->user()->id;
        $permissions = User::userPermissions($userId)->toArray();
        $permissions = array_column($permissions, 'slug');
        session(['permissions' => $permissions]);
    }
}

if (! function_exists('acl')) {
    function acl($permission) {
        $sessionPerissions =  session('permissions');
        return $sessionPerissions 
            ? in_array($permission, $sessionPerissions)
            : false;
    }
}

if (! function_exists('guard')) {
    function guard($permission) {
        abort_if(!acl($permission), 403);
    }
}

if (! function_exists('get_case_type_id')) {
    function get_case_type_id($case_type) {
        $model = CaseType::where('case_type', trim($case_type))->first();
        return $model ? $model->id : null;
    }
}

if (! function_exists('get_case_type_by_id')) {
    function get_case_type_by_id($id) {
        $model = CaseType::find($id);
        return $model ? $model->case_type : '';
    }
}

if (! function_exists('get_court_id')) {
    function get_court_id($court_number) {
        $model = Court::where('court_number', trim($court_number))->first();
        return $model ? $model->id : null;
    }
}

if (! function_exists('get_bench_id')) {
    function get_bench_id($bench_name) {
        $model = Bench::where('bench_name', trim($bench_name))->first();
        return $model ? $model->id : null;
    }
}

if (! function_exists('get_status_id')) {
    function get_status_id($status) {
        $model = Status::where('status', trim($status))->first();
        return $model ? $model->id : null;
    }
}

if (! function_exists('get_category_id')) {
    function get_category_id($name) {
        $model = Category::where('name', trim($name))->first();
        return $model ? $model->id : null;
    }
}

if (! function_exists('get_category_by_id')) {
    function get_category_by_id($id) {
        $model = Category::find($id);
        return $model ? $model->name : '';
    }
}

if (! function_exists('case_numbers')) {
    function case_numbers() {
        $model = ManageCaseMaster::findAll();
        return $model
            ? make_list($model, 'case_number', 'case_number', false)
            : [];
    }
}

if (! function_exists('is_role')) {
    function is_role($role_id, $role_type) {
        return intval($role_id) === intval($role_type);
    }
}

if (! function_exists('db_date')) {
    function db_date($date, $format) {
        return Carbon::createFromFormat($format, $date);
    }
}

if (! function_exists('status_id')) {
    function status_id($status) {
        $status = Status::status($status)->first();
        return $status ? $status->id : null;
    }
}