<?php namespace CupOfTea\EasyCfg\Facades;

use Illuminate\Support\Facades\Facade;
use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;

/**
 * @see CupOfTea\EasyCfg\EasyCfg
 */
class Cfg extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ProviderContract::class;
    }
}
