<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // User management & backups: SuperAdmin, OrgHead, Admin
        Gate::define('manage-users', fn (User $user) => $user->isAdmin());

        // Creating / editing / deleting records: everyone except Viewer
        Gate::define('edit-records', fn (User $user) => $user->isStaff());

        // Deleting records, files and patients (incl. permanent patient removal): admins only
        Gate::define('delete-records', fn (User $user) => $user->isAdmin());
    }
}
