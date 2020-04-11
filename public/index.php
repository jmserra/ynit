<?php

require_once '../bootstrap.php';

if(is_prod())
    error_reporting(0);
else
    error_reporting(E_ALL);

$router = make('router');
$router->get('/', 'Controller@homepage');
$router->get('/somepage/{bar}/is/{foo}', 'Controller@somepage');
$router->get('/status', function(){ return 'alive'; });
$router->serve();
