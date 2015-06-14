<?php namespace CupOfTea\EasyCfg;

use App;

use Illuminate\Database\Eloquent\Model;

use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;

trait Configurable
{

    /**
     * Object Config items.
     *
     * @var array
     */
    public $_cupoftea_easy_cfg = [];

    /**
     * If EasyCfg is observing Model Events.
     *
     * @var bool
     */
    private $_cupoftea_easy_cfg_observing = false;

    /**
     * Model Database fields.
     *
     * @var array
     */
    protected $fields = [];
    
    /**
	 * Delete the model from the database.
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
	public function delete()
    {
        if (is_a($this, Model::class)) {
            App::make(ProviderContract::class)->deleteAll(get_class($this), $this->primaryKey);
        }
        
        return parent::delete();
    }
    
    /**
	 * Dynamically retrieve attributes on the Class.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
    public function __get($key)
	{
		if (isset($this->$key)) {
            return $this->$key;
        }
        
        if (is_a($this, Model::class) && $attribute = $this->getAttribute($key)) {
            return $attribute;
        }
        
        $cfg = App::make(ProviderContract::class);
        
        if (isset($this->id) || isset($this->primaryKey)) {
            return $cfg->get($key, get_class($this), isset($this->primaryKey) ? $this->primaryKey : $this->id);
        } else {
            return $cfg->get($key, get_class($this));
        }
	}
    
	/**
	 * Dynamically set attributes on the Class.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		if (isset($this->$key)) {
            $this->$key = $value;
            return;
        }
        
        if (is_a($this, Model::class) && in_array($key, $this->fields)) {
            $this->setAttribute($key, $value);
            return;
        }
        
        $cfg = App::make(ProviderContract::class);
        
        if (is_a($this, Model::class) && !config('easycfg.autosave')) {
            $this->_cupoftea_easy_cfg[$key] = $value;
            
            if (!$this->_cupoftea_easy_cfg_observing) {
                $this->saved(function($model) use ($cfg) {
                    foreach($model->_cupoftea_easy_cfg as $key => $value) {
                        if ($value === null) {
                            $cfg->delete($key, get_class($model), $model->primaryKey);
                        } else {
                            $cfg->set($key, $value, get_class($model), $model->primaryKey);
                        }
                    }
                });
                
                $this->_cupoftea_easy_cfg_observing = true;
            }
        } else {
            if (isset($this->id) || isset($this->primaryKey)) {
                return $cfg->set($key, $value, get_class($this), isset($this->primaryKey) ? $this->primaryKey : $this->id);
            } else {
                return $cfg->set($key, $value, get_class($this));
            }
        }
	}
    
    /**
	 * Dynamically remove attributes on the Class.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
    function __unset($key)
    {
        $cfg = App::make(ProviderContract::class);
        
        if (is_a($this, Model::class) && !config('easycfg.autosave')) {
            $this->_cupoftea_easy_cfg[$key] = null;
            
            if (!$this->_cupoftea_easy_cfg_observing) {
                $this->saved(function($model) use ($cfg) {
                    foreach($model->_cupoftea_easy_cfg as $key => $value) {
                        if ($value === null) {
                            $cfg->delete($key, get_class($model), $model->primaryKey);
                        } else {
                            $cfg->set($key, $value, get_class($model), $model->primaryKey);
                        }
                    }
                });
                
                $this->_cupoftea_easy_cfg_observing = true;
            }
        } else {
            if (isset($this->id) || isset($this->primaryKey)) {
                return $cfg->delete($key, get_class($this), isset($this->primaryKey) ? $this->primaryKey : $this->id);
            } else {
                return $cfg->delete($key, get_class($this));
            }
        }
    }
    
}
