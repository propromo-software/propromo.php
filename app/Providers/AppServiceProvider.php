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
use Illuminate\Support\Facades\Blade;

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

        // Sets an attribute if the value is defined and removes the attribute if undefined.
        Blade::directive('wcSetAttribute', function ($arguments) {
            list($attribute, $condition) = explode(',', $arguments);
            $attribute = trim(str_replace(['"', "'"], '', $attribute));
            $condition = trim($condition);
            return "<?php echo {$condition} ? '{$attribute}' : '!{$attribute}' ?>";
        });
      
        // Creates a model binding for sl-input
        Blade::directive('slInputModel', function ($arguments) {
            list($expression, $value) = explode(',', str_replace([' ', '"', "'"], '', $arguments));
            return "value=\"<?php echo {$value}; ?>\" 
                x-on:sl-input=\"\$wire.\$set('{$expression}', \$event.target.value)\"
                x-on:sl-change=\"\$wire.\$set('{$expression}', \$event.target.value)\"";
        });

        // Creates a model binding for sl-checkbox
        Blade::directive('slCheckboxModel', function ($arguments) {
            list($expression, $value) = explode(',', str_replace([' ', '"', "'"], '', $arguments));
            return "<?php echo {$value} ? 'checked' : '' ?> x-on:sl-change=\"\$wire.set('{$expression}', \$el.checked);\"";
        });
      
        // Creates a model binding for sl-select including multiple select
        Blade::directive('slSelectModel', function ($arguments) {
            list($expression, $value) = explode(',', str_replace([' ', '"', "'"], '', $arguments));
            return "value=\"<?php echo is_array({$value}) ? implode(' ', {$value}) : {$value}; ?>\" x-on:sl-change=\"\$wire.set('{$expression}', \$el.value);\"";
        });
    
        // Creates a model binding for sl-radio-group
        Blade::directive('slRadioGroupModel', function ($arguments) {
            list($expression, $value) = explode(',', str_replace([' ', '"', "'"], '', $arguments));
            return "value=\"<?php echo {$value}; ?>\" x-on:sl-change=\"\$wire.set('{$expression}', \$el.value);\"";
        });
    }
}
