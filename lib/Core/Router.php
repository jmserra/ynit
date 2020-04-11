<?php

namespace Lib\Core;

use Exception;

class Router
{
    private $uri;
    private $method;
    private $notfound;
    private $routes = [];
    private $namespace = 'App';

    public function __construct()
    {
        $request = new Request;
        $this->uri = $request->getUri();
        $this->method = $request->getMethod();
        $this->notfound = function(){ return '404 - Not Found.';};
    }

    public function any($pattern, $handler)
    {
        $this->route('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $handler);
    }

    public function get($pattern, $handler)
    {
        $this->route('GET', $pattern, $handler);
    }

    public function post($pattern, $handler)
    {
        $this->route('POST', $pattern, $handler);
    }

    public function patch($pattern, $handler)
    {
        $this->route('PATCH', $pattern, $handler);
    }

    public function delete($pattern, $handler)
    {
        $this->route('DELETE', $pattern, $handler);
    }

    public function put($pattern, $handler)
    {
        $this->route('PUT', $pattern, $handler);
    }

    public function options($pattern, $handler)
    {
        $this->route('OPTIONS', $pattern, $handler);
    }

    public function route($methods, $pattern, $handler)
    {
        foreach (explode('|', $methods) as $method)
        {
            $this->routes[$method][] = [
                'pattern' => '/' . trim($pattern, '/'),
                'handler' => $handler,
            ];
        }
    }

    public function serve()
    {
        $handledRoute = null;

        if(isset($this->routes[$this->method]))
        {
            foreach($this->routes[$this->method] as $route)
            {
                $params = $this->matchRoute($route);
                if($params !== false)
                {
                    $this->handle($route['handler'], $params);
                    $handledRoute = $route;
                    break;
                }
            }
        }

        if (!$handledRoute)
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            $this->handle($this->notfound);
        }
    }

    private function matchRoute($route)
    {
        $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $route['pattern']);

        $numMatches = preg_match_all('#^' . $pattern . '$#', $this->uri, $matches, PREG_OFFSET_CAPTURE);
        if(!$numMatches)
            return false;

        $params = [];
        $matches = array_slice($matches, 1);
        foreach($matches as $index=>$match)
        {
            if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0]))
            {
                $params[]=trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
            }
            else
            {
                $params[]=isset($match[0][0]) ? trim($match[0][0], '/') : null;
            }
        }
        return $params;
    }

   private function handle($handler, $params = [])
   {
        if (is_callable($handler))
        {
            $response = call_user_func_array($handler, $params);
        }
        elseif(stripos($handler, '@') !== false)
        {
            list($controller, $function) = explode('@', $handler);

            $controller = $this->namespace . '\\' . $controller;

            if(!class_exists($controller))
                throw new Exception("Controller {$controller} not found");

            $handler = new $controller();

            if(!method_exists($handler, $function))
                throw new Exception("Function {$function} is not callable in Controller: {$controller}");

            $response = call_user_func_array([new $controller(), $function], $params);
        }

        if(!is_null($response))
        {
            echo $response;
        }
    }
}
