<?php namespace CupOfTea\EasyCfg;

class Compiler
{
    
    protected $value;
    protected $parentCompiler;
    
    public function compile($value, $parentCompiler)
    {
        $value = preg_replace('/@cfg\((\'|")((?:(?!\1).)*)\1\)/i', '@yield($1_cfg_$2$1)', $value);
        $value = preg_replace('/@cfg\((\'|")((?:(?!\1).)*)\1,\s*(\'|")((?:(?!\3).)*)\3\)/i', "@section($1_cfg_$2$1)\n$4\n@endsection", $value);
        
        return $value;
    }
}
