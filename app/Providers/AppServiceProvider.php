<?php

namespace App\Providers;

use App\Models\Donor;
use Illuminate\Support\ServiceProvider;
use Modules\Donor\App\Interfaces\DonorRepositoryInterface;
use Modules\Donor\App\Repositories\DonorRepository;
use Modules\Donor\App\Services\DonorService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(DonorRepositoryInterface::class, DonorRepository::class);
        $this->app->bind(DonorService::class, function ($app) {
            return new DonorService($app->make(DonorRepositoryInterface::class));
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
