<?php namespace CupOfTea\EasyCfg;

class Compiler
{

    /**
     * Compile @cfg directives.
     *
     * @param $value
     * @return mixed
     */
    public function compile($value)
    {
        $value = preg_replace('/@cfg\((\'|")((?:(?!\1).)*)\1\)/i', '@yield($1_cfg_$2$1)', $value);
        $value = preg_replace('/@cfg\((\'|")((?:(?!\1).)*)\1,\s*(\'|")((?:(?!\3).)*)\3\)/i', "@section($1_cfg_$2$1)$4 @endsection", $value);
        
        return $value;
    }
}
