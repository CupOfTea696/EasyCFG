<?php namespace CupOfTea\EasyCfg;

use DB;
use Closure;

use CupOfTea\Package\Package;
use CupOfTea\EasyCfg\Exceptions\InvalidKeyException;
use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;

use Illuminate\Foundation\Application;

class EasyCfg implements ProviderContract
{
    
    use Package;
    
    /**
     * Package Info
     *
     * @const string
     */
    const PACKAGE = 'CupOfTea/EasyCfg';
    const VERSION = '1.0.0';
    
    protected $all = [];
    protected $cfg = [];
    protected $all_id = [];
    protected $cfg_id = [];
    
    /**
     * Get the Configurable item.
     *
     * @param  mixed  $configurable
     * @return mixed
     */
    protected function getConfigurable($configurable)
    {
        if (is_a(Application::class, $configurable)) {
            return null;
        }

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
    protected function getConfigurableId($configurable, $configurable_id)
    {
        if($configurable_id !== null){
            return $configurable_id;
        }
        
        if (is_object($configurable) && isset($configurable->id)) {
            return $configurable->id;
        }
        
        return $configurable_id;
    }
    
    /**
     * @param $all
     * @return array
     */
    protected function mapAll($all)
    {
        $map = [];
        foreach ($all as $result) {
            $map[$this->key($result)] = $this->value($result);
        }
        
        return $map;
    }
    
    /**
     * @param $k
     * @param $id
     * @return mixed
     */
    protected function getValues($k = null, $id = null)
    {
        if ($k === null) {
            return isset($this->all[Application::class]) ? $this->all[Application::class] : null;
        } elseif ($id === null) {
            return isset($this->all[$k]) ? $this->all[$k] : null;
        } else {
            return isset($this->all_id[$k]) ? isset($this->all_id[$k][$id]) ? $this->all_id[$k][$id] : null : null;
        }
    }
    
    /**
     * @param $k
     * @param $id
     * @return void
     */
    protected function unsetValues($k = null, $id = null)
    {
        if ($k === null) {
            if (isset($this->all[Application::class])) {
                unset($this->all[Application::class]);
            }
        } elseif ($id === null) {
            if (isset($this->all[$k])){
                unset($this->all[$k]);
            }
        } else {
            if (isset($this->cfg_id[$k]) && isset($this->cfg_id[$k][$id])) {
                unset($this->cfg_id[$k][$id]);
            }
        }
    }
    
    /**
     * @param $key
     * @param $configurable
     * @param $configurable_id
     * @return mixed
     */
    protected function getValue($key, $configurable = null, $configurable_id = null)
    {
        $k = $configurable ? $configurable . ':' . $key : $key;
        
        if ($configurable_id !== null) {
            return isset($this->cfg_id[$k]) ? isset($this->cfg_id[$k][$configurable_id]) ? $this->cfg_id[$k][$configurable_id] : null : null;
        } else {
            return isset($this->cfg[$k]) ? $this->cfg[$k] : null;
        }
    }
    
    /**
     * @param $key
     * @param $configurable
     * @param $configurable_id
     * @return mixed
     */
    protected function setValue($key, $result, $configurable = null, $configurable_id = null)
    {
        $k = $configurable ? $configurable . ':' . $key : $key;
        $value = $this->value($result);
        
        if ($configurable_id !== null) {
            return $this->cfg_id[$k][$configurable_id] = $value;
        } else {
            return $this->cfg[$k] = $value;
        }
    }
    
    /**
     * @param $key
     * @param $configurable
     * @param $configurable_id
     * @return void
     */
    protected function unsetValue($key, $configurable = null, $configurable_id = null)
    {
        $k = $configurable ? $configurable . ':' . $key : $key;
        
        if ($configurable_id !== null) {
            if (isset($this->cfg_id[$k]) && isset($this->cfg_id[$k][$configurable_id])) {
                unset($this->cfg_id[$k][$configurable_id]);
            }
        } else {
            if (isset($this->cfg[$k])){
                unset($this->cfg[$k]);
            }
        }
    }
    
    /**
     * @param $value
     * @return mixed
     */
    protected function key($result)
    {
        if ($result === null || !isset($result->key)) {
            return null;
        }
        
        return $result->key;
    }
    
    /**
     * @param $value
     * @return mixed
     */
    protected function value($result)
    {
        if ($result === null || !isset($result->value)) {
            return null;
        } else {
            $value = $result->value;
        }
        
        $json = json_decode($value);
        
        return $json ? $json : (string)$value;
    }
    
    /**
     * @inheritdoc
     */
    public function all($configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable, $configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if ($values = $this->getValues($configurable, $configurable_id) !== null) {
            return $values;
        }
        
        if ($configurable === null) {
            $result = DB::table(config('easycfg.table'))
                ->whereNull('configurable')
                ->get();
            
            return $this->all[Application::class] = $this->mapAll($result);
        } elseif ($configurable_id === null) {
            $result = DB::table(config('easycfg.table'))
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->get();
            
            return $this->all[$configurable] = $this->mapAll($result);
        } else {
            $result = DB::table(config('easycfg.table'))
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->orWhere(function($query) use ($configurable, $configurable_id) {
                    $query->where('configurable', $configurable)
                          ->where('configurable_id', $configurable_id);
                })
                ->get();
            
            return $this->all_id[$configurable][$configurable_id] = $this->mapAll($result);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function get($key, $configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable, $configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if ($value = $this->getValue($key, $configurable, $configurable_id) !== null) {
            return $value;
        }
        
        if ($configurable === null) {
            $result = DB::table(config('easycfg.table'))
                ->select('value')
                ->where('key', $key)
                ->whereNull('configurable')
                ->first();
            
            return $this->setValue($key, $result);
        } elseif ($configurable_id === null) {
            $result = DB::table(config('easycfg.table'))
                ->select('value')
                ->where('key', $key)
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->first();
            
            return $this->setValue($key, $result, $configurable);
        } else {
            $result = DB::table(config('easycfg.table'))
                ->select('value')
                ->where('key', $key)
                ->where('configurable', $configurable)
                ->where('configurable_id', $configurable_id)
                ->first();
            
            return $this->setValue($key, $result, $configurable, $configurable_id);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function set($key, $value, $configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable, $configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        if (str_contains($key, ':')) {
            throw new InvalidKeyException('The character \':\' is not allowed in the Configuration key.');
        } elseif (strlen($key) > 128) {
            throw new InvalidKeyException('The Configuration key is too long (max length is 128 characters).');
        }
        
        if ($value instanceof Closure) {
            $value = $value();
        }
        
        $this->setValue($key, $value, $configurable, $configurable_id);
        
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }
        
        $value = (string)$value;
        
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
        $configurable_id = $this->getConfigurableId($configurable, $configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        $this->unsetValue($key, $configurable, $configurable_id);
        
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
    
    /**
     * @inheritdoc
     */
    public function deleteAll($configurable = null, $configurable_id = null)
    {
        $configurable_id = $this->getConfigurableId($configurable, $configurable_id);
        $configurable = $this->getConfigurable($configurable);
        
        $this->unsetValues($configurable, $configurable_id);
        
        if ($configurable === null) {
            return DB::table(config('easycfg.table'))
                ->whereNull('configurable')
                ->delete();
        } elseif ($configurable_id === null) {
            return DB::table(config('easycfg.table'))
                ->where('configurable', $configurable)
                ->whereNull('configurable_id')
                ->delete();
        } else {
            return DB::table(config('easycfg.table'))
                ->where('configurable', $configurable)
                ->where('configurable_id', $configurable_id)
                ->delete();
        }
    }
    
}