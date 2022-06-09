<?php
 
namespace App\Observers;
use Carbon\Carbon;
use App\Modules\AuditTrail\AuditTrail;   
use App\Modules\Status\Status;

class StatusObserver
{
    public $moduleName = 'Status';
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(Status $Status)
    {   
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Status',
            'activity_type' => 'Add Status',
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
    public function updated(Status $Status)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Status', 
            'activity_type' => 'Update Status',
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
    public function deleted(Status $Status)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Status', 
            'activity_type' => 'Deleted Status',
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
    public function restored(Status $Status)
    {
        //
    }
 
    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(Status $Status)
    {
        //
    }
}