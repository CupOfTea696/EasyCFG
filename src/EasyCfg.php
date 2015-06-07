<?php namespace CupOfTea\EasyCfg;

use DB;

use CupOfTea\Package\Package;
use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;

class EasyCfg implements ProviderContract
{
    
    use Package;
    
    /**
     * Package Info
     *
     * @const string
     */
    const PACKAGE = 'CupOfTea/EasyCfg';
    const VERSION = '0.0.0';
    
    protected $all = [];
    protected $cfg = [];
    protected $cfg_id = [];
    
    /**
     * Get the Configurable item.
     *
     * @param  mixed  $configurable
     * @return mixed
     */
    protected function getConfigurable($configurable)
    {
        if (is_object($configurable)) {
            return get_class($configurable);
        }
        
        return $configurable;
    }
    
    /**
     * Get the Configurable item's Id.
     *
     * @param  mixed  $configurable
     * @param  mixed  $configurable_id
     * @return mixed
     */
    protected getConfigurableId($configurable, $configurable_id)
    {
        if($configurable_id !== null){
            return $configurable_id;
        }
        
        if (is_object($configurable) && isset($configurable->id)) {
            return $configurable->id;
        }
        
        return $configurable_id;
    }
    
    protected function mapAll($all)
    {
        $map = [];
        foreach ($all as $key => $value) {
            $map[$key] = $value;
        }
        
        return $map;
    }
    
    protected function getValues($configurable, $configurable_id)
    {
        
    }
    
    protected function getValue($key, $configurable, $configurable_id)
    {
        $k = $configurable ? $configurable . ':' . $key : $key;
        
        if ($configurable_id !== null) {
            return isset($this->cfg_id[$k]) ? isset($this->cfg_id[$k][$configurable_id]) ? $this->cfg_id[$k][$configurable_id] : null : null;
        } else {
            return isset($this->cfg[$k]) ? $this->cfg[$k] : null;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function all($configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if ($configurable === null) {
            $result = DB::table(config('easycfg.table'))
                ->whereNull('configurable')
                ->get();
            
            return mapAll($result);
        } elseif ($configurable_id === null) {
            $result = DB::table(config('easycfg.table'))
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->get();
            
            return mapAll($result);
        } else {
            $result = DB::table(config('easycfg.table'))
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->orWhere(function($query) use ($configurable, $configurable_id) {
                    $query->where('configurable', $configurable)
                          ->where('configurable_id', $configurable_id);
                })
                ->get();
            
            return mapAll($result);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function get($key, $configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if ($value = $this->getValue($key, $configurable, $configurable_id) !== null) {
            return $value;
        }
        
        if ($configurable === null) {
            return $this->cfg[$key] = DB::table(config('easycfg.table'))
                ->select('value')
                ->where('key', $key)
                ->whereNull('configurable')
                ->first()
                ->value;
        } elseif ($configurable_id === null) {
            return $this->cfg[$configurable . ':' . $key] = DB::table(config('easycfg.table'))
                ->select('value')
                ->where('key', $key)
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->first()
                ->value;
        } else {
            return $this->cfg_id[$configurable . ':' . $key][$configurable_id] = DB::table(config('easycfg.table'))
                ->select('value')
                ->where('key', $key)
                ->where('configurable', $configurable)
                ->where('configurable_id', $configurable_id)
                ->first()
                ->value;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function set($key, $value, $configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if ($configurable === null) {
            if ($this->get($key)) {
                return DB::table(config('easycfg.table'))
                    ->where('key', $key)
                    ->whereNull('configurable')
                    ->update(['value' => $value]);
            } else {
                return DB::table(config('easycfg.table'))
                    ->insert(['key' => $key, 'value' => $value]);
            }
        } elseif ($configurable_id === null) {
            if ($this->get($key, $configurable)) {
                return DB::table(config('easycfg.table'))
                    ->where('configurable', $configurable)
                    ->update(['value' => $value]);
            } else {
                return DB::table(config('easycfg.table'))
                    ->insert(['key' => $key, 'value' => $value, 'configurable' => $configurable]);
            }
        } else {
            if ($this->get($key, $configurable, $configurable_id)) {
                return DB::table(config('easycfg.table'))
                    ->where('configurable', $configurable)
                    ->where('configurable_id', $configurable_id)
                    ->update(['value' => $value]);
            } else {
                return DB::table(config('easycfg.table'))
                    ->insert(['key' => $key, 'value' => $value, 'configurable' => $configurable, 'configurable_id' => $configurable_id]);
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function delete($key, $configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if ($configurable === null) {
            return DB::table(config('easycfg.table'))
                ->where('key', $key)
                ->whereNull('configurable')
                ->delete();
        } elseif ($configurable_id === null) {
            return DB::table(config('easycfg.table'))
                ->where('key', $key)
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->delete();
        } else {
            return DB::table(config('easycfg.table'))
                ->where('key', $key)
                ->where('configurable', $configurable)
                ->where('configurable_id', $configurable_id)
                ->delete();
        }
    }
    
}
