<?php namespace CupOfTea\EasyCfg;

use CupOfTea\EasyCfg\EasyCfg;
use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;

class EasyCfgServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Available commands in this package
     *
     * @var array
     */
    protected $commands = [
        
    ];
    
    /**
     * Bootstrap the application events.
     *
     * @param  \CupOfTea\TwoStream\Routing\WsRouter  $router
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/easycfg.php' => config_path('easycfg.php'),
        ], 'cfg');
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/easycfg.php', 'easycfg'
        );
        
        $this->app->bindShared('command.twostream.listen', function($app){
            return new Server($app);
        });
        
        $this->commands($this->commands);
        
        $this->app->bindShared(ProviderContract::class, EasyCfg::class);
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'CupOfTea\TwoStream\Contracts\Factory',
            'CupOfTea\TwoStream\Contracts\Session\ReadOnly',
        ];
    }
    
}
