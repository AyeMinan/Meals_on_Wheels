<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Caregiver\App\Interfaces\CaregiverRepositoryInterface;
use Modules\Caregiver\App\Repositories\CaregiverRepository;
use Modules\Caregiver\App\Services\CaregiverService;
use Modules\Member\App\Interfaces\MemberRepositoryInterface;
use Modules\Member\App\Services\MemberService;
use Modules\Member\Repositories\MemberRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(CaregiverRepositoryInterface::class, CaregiverRepository::class);
        $this->app->bind(CaregiverService::class, function ($app) {
            return new CaregiverService($app->make(CaregiverRepositoryInterface::class));
        });
        $this->app->bind(MemberService::class, function ($app) {
            return new MemberService($app->make(MemberRepositoryInterface::class));

        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
