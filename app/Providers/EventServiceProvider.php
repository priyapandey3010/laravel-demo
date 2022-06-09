<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use App\Modules\Bench\Bench;
use App\Modules\CaseType\CaseType;
use App\Modules\Cases\Cases;
use App\Modules\Court\Court;
use App\Modules\Status\Status;
use App\Modules\Category\Category;
use App\Modules\Department\Department;
use App\Modules\Designation\Designation;
use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use App\Modules\DisplayBoardUploads\DisplayBoardUploads;
use App\Observers\{
    UserObserver,
    BenchObserver,
    CaseObserver,
    CasesObserver,
    CourtObserver,
    StatusObserver,
    CategoryObserver,
    DepartmentObserver,
    DesignationObserver,
    PermissionObserver,
    RoleObserver,
    DisplayBoardUploadsObserver,
    DisplayBoardObserver,
};

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Bench::observe(BenchObserver::class);
        CaseType::observe(CaseObserver::class);
       Cases::observe(CasesObserver::class);
        Court::observe(CourtObserver::class);
        Status::observe(StatusObserver::class);
        Category::observe(CategoryObserver::class);
        Department::observe(DepartmentObserver::class);
        Designation::observe(DesignationObserver::class);
        Permission::observe(PermissionObserver::class);
        Role::observe(RoleObserver::class);
        DisplayBoardUploads::observe(DisplayBoardUploadsObserver::class);
        //Cases::observe(DisplayBoardObserver::class);
    }
}
