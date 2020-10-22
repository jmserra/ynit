<?php
namespace Lib\Core;

class Model  implements \ArrayAccess
{
    protected $props;

    public function __construct($init = [])
    {
        if(is_object($init))
        {
            $this->props = $init->toArray();
        }

        if(is_array($init))
        {
            $this->props = $init;
        }
    }

    public function __get($name)
    {
        if(!isset($this->props[$name]))
            return null;

        return $this->props[$name];
    }

    public function __isset($name)
    {
        return isset($this->props[$name]);
    }

    public function __set($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function offsetExists ( $offset )
    {
        return isset($this->$offset);
    }

    public function offsetGet ( $offset )
    {
        return isset($this->$offset) ? $this->$offset : null;
    }

    public function offsetSet ( $offset , $value )
    {
        $this->$offset = $value;
    }

    public function offsetUnset ( $offset )
    {
        $this->$offset = null;
    }

    public function toArray()
    {
        return $this->props;
    }
}
