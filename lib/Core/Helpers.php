<?php

function dd()
{
    foreach(func_get_args() as $var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    exit;
}

function abort($code, $msg="")
{
    switch(intval($code)){
        case 404: $err = "Not Found"; break;
        case 500: $err = "Server Error"; break;
        default: $err = "Error";
    }

    if(isset($_SERVER['SERVER_PROTOCOL']))
        header($_SERVER['SERVER_PROTOCOL'] . ' '.$code.' '.$err);

    echo $msg;
    exit();
}

function env($key, $default=null)
{
    $value = getenv($key);
    if(!empty($value))
        return $value;

    return $default;
}

function load_dot_env($file)
{
    foreach(parse_ini_file($file) as $k=>$v)
    {
        putenv("{$k}={$v}");
    }
}

function request()
{
    return new Lib\Core\Request;
}

function is_prod()
{
    return env('APP_ENV','production') == 'production';
}

function handle_exception($err)
{
    if(is_prod())
        $msg = 'An Error Occurred';
    else
        $msg = $err;

    abort(500, $msg);
}

function handle_error($errno,$errstr,$file,$line)
{
    $msg = $errstr.' in '.$file.':'.$line;

    if(is_prod())
    {
        if($errno == E_USER_ERROR)
        {
            abort(500);
        }
    }
    else
    {
        handle_exception($msg);
    }
}

function init_psr4_autoloader($namespace='App', $folder='src')
{
    spl_autoload_register( function( $class ) use ($namespace, $folder) {
        // Continue if the class is not in our namespace.
        if ( 0 !== strpos( $class, $namespace ) ) {
            return;
        }

        // Build a class filename to append to the path.
        $suffix = ltrim( str_replace( $namespace, '', $class ), '\\' );
        $suffix = DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $suffix ) . '.php';

        // Load the class file if it exists and return.
        if ( file_exists( $file = realpath( ROOTPATH.'/'.$folder ) . $suffix ) ) {
            include $file;
            return;
        }
    }, true, true );
}

function make($key, $arguments = [])
{
    if(FALSE === strpos($key, '.'))
        $key = 'core.'.$key;

    $key = str_ireplace(' ','\\',ucwords(str_ireplace('.', ' ', $key)));
    $class = 'Lib\\'.$key;
    if(!class_exists($class))
        throw new Exception("Cant make {$key} unable to find related class: {$class}");

    return new $class(...$arguments);
}

function makeOne($key, $arguments = [])
{
    global $ynit_singleton;

    if(isset($ynit_singleton[$key]))
        return $ynit_singleton[$key];

    return $ynit_singleton[$key] = make($key, $arguments);
}

function blade($file, $data = [])
{
    return makeOne('HTML.blade')->run($file,$data);
}

function collect(...$data)
{
    return new Lib\Core\Collection(...$data);
}
