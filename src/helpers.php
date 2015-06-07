<?php

if (!function_exists('cfg')) {
    function cfg($key = null, $configurable = null, $configurable_id = null)
    {
        if ($key === null) {
            return Cfg::getFacadeRoot();
        } else {
            return Cfg::get($key, $configurable, $configurable_id);
        }
    }
}
