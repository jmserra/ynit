<?php

define('ROOTPATH', realpath(__DIR__));
define('PUBLICPATH', realpath(__DIR__.'/public'));
define('APPPATH', realpath(__DIR__.'/app'));
define('LIBPATH', realpath(__DIR__.'/lib'));

require_once LIBPATH . '/Core/Helpers.php';

init_psr4_autoloader('Lib','lib');
init_psr4_autoloader('App','app');

set_error_handler('handle_error');
set_exception_handler('handle_exception');

if(file_exists(ROOTPATH.'/.env'))
    load_dot_env(ROOTPATH.'/.env');
