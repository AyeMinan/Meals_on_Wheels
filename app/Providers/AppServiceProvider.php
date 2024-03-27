<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Caregiver\App\Interfaces\CaregiverRepositoryInterface;
use Modules\Caregiver\App\Repositories\CaregiverRepository;
use Modules\Caregiver\App\Services\CaregiverService;
use Modules\Donor\App\Interfaces\DonorRepositoryInterface;
use Modules\Donor\App\Repositories\DonorRepository;
use Modules\Donor\App\Services\DonorService;
use Modules\Member\App\Interfaces\MemberRepositoryInterface;
use Modules\Member\App\Services\MemberService;
use Modules\Member\Repositories\MemberRepository;
use Modules\Partner\App\Interface\PartnerInterface;
use Modules\Partner\App\Repository\PartnerRepository;
use Modules\Partner\App\Service\PartnerService;
use Modules\Volunteer\App\Interface\VolunteerInterface;
use Modules\Volunteer\App\Repository\VolunteerRepository;
use Modules\Volunteer\App\Service\VolunteerService;

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
        $this->app->bind(DonorRepositoryInterface::class, DonorRepository::class);

        $this->app->bind(PartnerInterface::class, PartnerRepository::class);

        $this->app->bind(VolunteerInterface::class, VolunteerRepository::class);



        $this->app->bind(DonorService::class, function ($app) {
            return new DonorService($app->make(DonorRepositoryInterface::class));
        });
        $this->app->bind(CaregiverService::class, function ($app) {
            return new CaregiverService($app->make(CaregiverRepositoryInterface::class));
        });
        $this->app->bind(MemberService::class, function ($app) {
            return new MemberService($app->make(MemberRepositoryInterface::class));

        });

        $this->app->bind(PartnerService::class, function ($app) {
            return new PartnerService($app->make(PartnerInterface::class));

        });

        $this->app->bind(VolunteerService::class, function ($app) {
            return new VolunteerService($app->make(VolunteerInterface::class));

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
