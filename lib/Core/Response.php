<?php
namespace Lib\Core;

class Response
{
    private $data;
    private $type = 'Content-Type text/html; charset=UTF-8';

    public function __construct($data='')
    {
        $this->data = $data;
    }

    public function json($data)
    {
        $this->data = json_encode( $this->arrayize($data) );
        return $this;
    }

    public function __toString()
    {
        return $this->data;
    }

    private function arrayize($data)
    {
        if(!is_object($data) && !is_array($data)){
            return $data;
        }

        if(is_object($data))
            $data = $data->toArray();

        foreach($data as &$item) {
           $item = $this->arrayize($item);
        }

        return $data;
    }
}
