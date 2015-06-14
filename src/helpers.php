<?php

if (!function_exists('cfg')) {
    /**
     * @param  mixed  $key
     * @param  mixed  $configurable
     * @param  mixed  $configurable_id
     * @return mixed
     */
    function cfg($key = null, $configurable = null, $configurable_id = null)
    {
        if ($key === null) {
            return Cfg::getFacadeRoot();
        } else {
            return Cfg::get($key, $configurable, $configurable_id);
        }
    }
}
