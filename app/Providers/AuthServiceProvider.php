<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Models\WorkShift;
use App\Policies\LeaveRequestPolicy;
use App\Policies\WorkShiftPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        LeaveRequest::class => LeaveRequestPolicy::class,
        WorkShift::class => WorkShiftPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for leave management
        Gate::define('view-leave-requests', [LeaveRequestPolicy::class, 'viewAny']);
        Gate::define('create-leave-requests', [LeaveRequestPolicy::class, 'create']);
        Gate::define('approve-leave-requests', [LeaveRequestPolicy::class, 'approve']);
        Gate::define('view-leave-balances', [LeaveRequestPolicy::class, 'viewBalances']);
        Gate::define('view-all-leave-balances', [LeaveRequestPolicy::class, 'viewAllBalances']);

        // Define gates for shift management
        Gate::define('view-shifts', [WorkShiftPolicy::class, 'viewAny']);
        Gate::define('create-shifts', [WorkShiftPolicy::class, 'create']);
        Gate::define('edit-shifts', [WorkShiftPolicy::class, 'update']);
        Gate::define('delete-shifts', [WorkShiftPolicy::class, 'delete']);
        Gate::define('manage-shifts', [WorkShiftPolicy::class, 'manageAll']);
        Gate::define('view-all-shifts', [WorkShiftPolicy::class, 'viewAll']);
        Gate::define('start-shift', [WorkShiftPolicy::class, 'start']);
        Gate::define('complete-shift', [WorkShiftPolicy::class, 'complete']);
        Gate::define('cancel-shift', [WorkShiftPolicy::class, 'cancel']);
    }
}
