<?php

namespace App\Providers;

use App\Services\ContributionService;
use App\Services\DeploymentService;
use App\Services\IssueService;
use App\Services\MonitorCreatorService;
use App\Services\MonitorJoinerApiService;
use App\Services\MonitorJoinerService;
use App\Services\RepositoryFetcherService;
use App\Services\RepositoryIssueFetcherService;
use App\Services\TokenCreatorService;
use App\Services\VulnerabilityService;
use App\Traits\MonitorJoinerApi;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ContributionService::class, function ($app) {
            return new ContributionService();
        });
        $this->app->singleton(DeploymentService::class, function ($app) {
            return new DeploymentService();
        });
        $this->app->singleton(IssueService::class, function ($app) {
            return new IssueService();
        });
        $this->app->singleton(MonitorCreatorService::class, function ($app) {
            return new MonitorCreatorService();
        });
        $this->app->singleton(MonitorJoinerApiService::class, function ($app) {
            return new MonitorJoinerApiService();
        });
        $this->app->singleton(MonitorJoinerService::class, function ($app) {
            return new MonitorJoinerService();
        });
        $this->app->singleton(RepositoryFetcherService::class, function ($app) {
            return new RepositoryFetcherService();
        });
        $this->app->singleton(RepositoryIssueFetcherService::class, function ($app) {
            return new RepositoryIssueFetcherService();
        });
        $this->app->singleton(TokenCreatorService::class, function ($app) {
            return new TokenCreatorService();
        });
        $this->app->singleton(VulnerabilityService::class, function ($app) {
            return new VulnerabilityService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
