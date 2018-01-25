<?php

namespace Sharelov\Shortener\Utilities;

class UrlHasher
{
    protected $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
    
    /**
     * Generate a new hash
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public function make($length=null)
    {
        if(!$length || !is_numeric($length)){
            throw new \Exception("Hash length was not set.");
        }
        $pool = $this->pool;
        $hash = "";
        $max = strlen($pool);

        for ($i = 0; $i < $length; ++$i) {
            $hash .= $pool[random_int(0, $max - 1)];
        }

        return $hash;
    }
}
