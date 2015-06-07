<?php namespace CupOfTea\EasyCfg;

use App;

use Illuminate\Database\Eloquent\Model;

use CupOfTea\EasyCfg\Contracts\Provider as ProviderContract;

trait Configurable
{
    
    protected $fields = [];
    
    /**
	 * Dynamically retrieve attributes on the model.
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
        
        if (isset($this->id)) {
            return $cfg->get($key, get_class($this), $this->id);
        } else {
            return $cfg->get($key, get_class($this));
        }
	}
	/**
	 * Dynamically set attributes on the model.
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
        
        if (is_a($this, Model::class) && in_array($key, $fields)) {
            $this->setAttribute($key, $value);
            return;
        }
        
        $cfg = App::make(ProviderContract::class);
        
        if (isset($this->id)) {
            return $cfg->set($key, $value, get_class($this), $this->id);
        } else {
            return $cfg->set($key, $value, get_class($this));
        }
	}
    
}
