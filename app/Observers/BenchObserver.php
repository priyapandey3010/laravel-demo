<?php
 
namespace App\Observers;
use Carbon\Carbon;
use App\Modules\AuditTrail\AuditTrail;   
use App\Modules\Bench\Bench;

class BenchObserver
{
    public $moduleName = 'Bench';
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(Bench $Bench)
    {   
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Bench',
            'activity_type' => 'Add Bench',
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
    public function updated(Bench $Bench)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Bench', 
            'activity_type' => 'Update Bench',
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
    public function deleted(Bench $Bench)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'Bench', 
            'activity_type' => 'Deleted Bench',
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
    public function restored(Bench $Bench)
    {
        //
    }
 
    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(Bench $Bench)
    {
        //
    }
}