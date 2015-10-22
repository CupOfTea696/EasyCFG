<?php namespace CupOfTea\EasyCfg;

use Blade;
use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;
use Illuminate\Support\ServiceProvider;

class EasyCfgServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => base_path('/database/migrations'),
        ], 'migrations');
        
        $this->publishes([
            __DIR__ . '/../config/easycfg.php' => config_path('easycfg.php'),
        ], 'config');
        
        Blade::extend([with(new Compiler), 'compile']);
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/easycfg.php', 'easycfg'
        );
        
        $this->app->singleton(ProviderContract::class, EasyCfg::class);
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ProviderContract::class,
        ];
    }
}
