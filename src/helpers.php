<?php

if (! function_exists('cfg')) {
    /**
     * EasyCfg Helper.
     *
     * @param  mixed  $key
     * @param  mixed  $configurable
     * @param  mixed  $configurable_id
     * @return mixed
     */
    function cfg($key = null, $configurable = null, $configurable_id = null)
    {
        if ($key === null) {
            return app('CupOfTea\EasyCfg\Contracts\Provider');
        } else {
            return app('CupOfTea\EasyCfg\Contracts\Provider')->get($key, $configurable, $configurable_id);
        }
    }
}
