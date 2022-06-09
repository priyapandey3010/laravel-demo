<?php
 
namespace App\Observers;
use Carbon\Carbon;
use App\Modules\AuditTrail\AuditTrail; 
use App\Models\User;

class UserObserver
{
    public $moduleName = 'user';
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $audit)
    {
        $user = auth()->user(); 
        AuditTrail::create([
            'module_name' => 'user',
            'activity_type' => 'Add User',
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
    public function updated(User $audit)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'user',
            'activity_type' => 'Update User',
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
    public function deleted(AuditTrail $audit)
    {
        AuditTrail::create([
            'module_name' => 'user',
            'activity_type' => 'Deleted User',
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
    public function restored(AuditTrail $audit)
    {
        //
    }
 
    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(AuditTrail $audit)
    {
        //
    }
}