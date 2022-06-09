<?php
 
namespace App\Observers;
use Carbon\Carbon;
use App\Modules\AuditTrail\AuditTrail;   
use App\Modules\DisplayBoardUploads\DisplayBoardUploads;

class DisplayBoardUploadsObserver
{
    public $moduleName = 'DisplayBoardUploads';
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(DisplayBoardUploads $DisplayBoardUploads)
    {   
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'DisplayBoardUploads',
            'activity_type' => 'Add DisplayBoardUploads',
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
    public function updated(DisplayBoardUploads $DisplayBoardUploads)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'DisplayBoardUploads', 
            'activity_type' => 'Update DisplayBoardUploads',
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
    public function deleted(DisplayBoardUploads $DisplayBoardUploads)
    {
        $user = auth()->user();
        AuditTrail::create([
            'module_name' => 'DisplayBoardUploads', 
            'activity_type' => 'Deleted DisplayBoardUploads',
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
    public function restored(DisplayBoardUploads $DisplayBoardUploads)
    {
        //
    }
 
    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(DisplayBoardUploads $DisplayBoardUploads)
    {
        //
    }
}