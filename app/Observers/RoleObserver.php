<?php
 
namespace App\Observers;
use Carbon\Carbon;
use App\Modules\AuditTrail\AuditTrail;   
use App\Modules\Role\Role;

class RoleObserver
{
    public $moduleName = 'Role';
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(Role $Role)
    {   
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Role',
            'activity_type' => 'Add Role',
            'user_name' => $user->username,
            'email_id' => $user->email,
            'role_id' => $user->role_id,
            'last_access' => $user->last_login_at,
            'last_login' => $user->last_login_at,
            'activity_datetime' => date('Y-m-d H:i:s'),  
            'ip_address' => $user->last_login_ip
        ]); 
    }
 
    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(Role $Role)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Role', 
            'activity_type' => 'Update Role',
            'user_name' => $user->username,
            'email_id' => $user->email,
            'role_id' => $user->role_id,
            'last_access' => $user->last_login_at,
            'last_login' => $user->last_login_at,
            'activity_datetime' => date('Y-m-d H:i:s'),  
            'ip_address' => $user->last_login_ip
        ]); 
        
    }
 
    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(Role $Role)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Role', 
            'activity_type' => 'Deleted Role',
            'user_name' => $user->username,
            'email_id' => $user->email,
            'role_id' => $user->role_id,
            'last_access' => $user->last_login_at,
            'last_login' => $user->last_login_at,
            'activity_datetime' => date('Y-m-d H:i:s'),  
            'ip_address' => $user->last_login_ip
        ]);
    }
 
    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(Role $Role)
    {
        //
    }
 
    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(Role $Role)
    {
        //
    }
}