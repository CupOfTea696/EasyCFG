<?php namespace CupOfTea\EasyCfg\Contracts;

interface Provider
{

    /**
     * Get all Configuration data.
     *
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return mixed
     */
    public function all($configurable = null, $configurable_id = null);

    /**
     * Get Configuration data by key.
     *
     * @param string $key
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return mixed
     */
	public function get($key, $configurable = null, $configurable_id = null);

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return mixed
     */
	public function set($key, $value, $configurable = null, $configurable_id = null);

    /**
     * @param $key
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return int
     */
    public function delete($key, $configurable = null, $configurable_id = null);
    
}
