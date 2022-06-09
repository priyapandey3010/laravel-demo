<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use App\Modules\AuditTrail\AuditTrail; 

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
     public function handle(Login $event)
    {
        $user = $event->user;
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->last_login_ip = $_SERVER['REMOTE_ADDR'];
        $user->save();
        $user = auth()->user(); 
        AuditTrail::create([
            'module_name' => 'Login',
            'activity_type' => 'User LoggedIn',
            'user_name' => $user->username,
            'email_id' => $user->email,
            'role_id' => $user->role_id,
            'last_access' => $user->last_login_at,
            'last_login' => $user->last_login_at,
            'activity_datetime' => date('Y-m-d H:i:s'),  
            'ip_address' => $user->last_login_ip
        ]); 
    }
}
