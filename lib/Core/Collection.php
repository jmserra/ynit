<?php
namespace Lib\Core;

use Exception;
use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Collection implements Countable, ArrayAccess, IteratorAggregate
{
    private $items = [];

    public function __construct()
    {
        foreach(func_get_args() as $arg)
        {
            if(is_array($arg))
                $this->appendMany($arg);
            else
                $this->append($arg);
        }
    }

    public function count()
    {
        return count($this->items);
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function appendMany($data)
    {
        foreach($data as $row)
        {
            $this->append($row);
        }
    }

    public function append($data)
    {
        $this->items[] = $this->makeItem($data);
    }

    public function first()
    {
        return reset($this->items);
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function offsetGet($key)
    {
        return @$this->items[$key];
    }

    public function offsetSet($key, $value)
    {
        $value = $this->makeItem($value);

        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    private function makeItem($data)
    {
        return $data;
    }
}
