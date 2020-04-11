<?php
namespace Lib\Core;

class Request
{
    private $bag;

    public function __construct($raw=null)
    {
        if($raw)
        {
            if(is_array($raw))
            {
                $this->bag = $raw;
            }
            else
            {
                $tryjson = @json_decode($raw,true);
                if(is_array($tryjson))
                    $this->bag = $tryjson;
            }
        }
        else
        {
            $this->bag = array_merge($_GET, $_POST);
        }
    }

    public function isEmpty()
    {
        return empty($this->bag);
    }

    public function has($key)
    {
        return isset($this->bag[$key]);
    }

    public function get($key)
    {
        if(!isset($this->bag[$key]))
            return null;

        return $this->bag[$key];
    }

    public function getUri()
    {
        $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), 1);
        if (strstr($uri, '?'))
            $uri = substr($uri, 0, strpos($uri, '?'));

        return '/' . trim($uri, '/');
    }

    public function getHeaders()
    {
       $headers = [];
       foreach ($_SERVER as $name => $value)
       {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
       }
       return $headers;
    }

    public function getMethod()
    {
        $method = @$_SERVER['REQUEST_METHOD'];

        if ($method == 'POST')
        {
            $headers = $this->getHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], ['PUT', 'DELETE', 'PATCH']))
            {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }
        return $method;
    }

    public function toArray()
    {
        return $this->bag;
    }

    public function __get($key)
    {
        return $this->get($key);
    }
}
