<?php namespace CupOfTea\EasyCfg\Contracts;

interface Provider
{
    
    /**
     * Get all Configuration values for a Configurable item.
     *
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return mixed
     */
    public function all($configurable = null, $configurable_id = null);
    
    /**
     * Get a Configuration value.
     *
     * @param string $key
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return mixed
     */
	public function get($key, $configurable = null, $configurable_id = null);
    
    /**
     * Set a Configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return mixed
     */
	public function set($key, $value, $configurable = null, $configurable_id = null);
    
    /**
     * Delete a Configuration value.
     *
     * @param $key
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return int
     */
    public function delete($key, $configurable = null, $configurable_id = null);

    /**
     * Delete all Configuration data on a Configurable item.
     *
     * @param mixed $configurable
     * @param mixed $configurable_id
     * @return int
     */
    public function deleteAll($configurable = null, $configurable_id = null);
    
}
