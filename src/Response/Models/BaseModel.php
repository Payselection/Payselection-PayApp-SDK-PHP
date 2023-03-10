<?php

namespace PaySelection\Response\Models;

use stdClass;

/**
 *
 */
class BaseModel
{
    public function fill(stdClass $fillData)
    {
        $props = get_object_vars($fillData);
        foreach ($props as $key => $value) {
            $lowerKey = lcfirst($key);
            $this->{$lowerKey} = $value;
        }
    }
}
