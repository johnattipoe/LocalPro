<?php

namespace App\Providers\AppServiceProvider;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\ProviderProfile;
use App\Models\SupportTicket;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->loadMigrationsFrom(File::directories(database_path('migrations')));

        View::composer('layouts.admin.admin', function ($view) {
            $view->with([
                'adminPendingCount' => ProviderProfile::where('verification_status', 'pending')->count(),
                'adminOpenTicketsCount' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
                'adminUnreadCount' => Notification::where('user_id', auth()->id())->whereNull('read_at')->count(),
            ]);
        });
    }
}
