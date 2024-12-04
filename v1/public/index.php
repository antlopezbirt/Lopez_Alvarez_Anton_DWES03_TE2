<?php

require '../core/Router.php';
require '../app/controllers/Items.php';

$url = $_SERVER['QUERY_STRING'];
echo 'QUERY_STRING = ' . $url . '<br>';
/* echo 'REQUEST_URI = ' . $_SERVER['REQUEST_URI']. '<br>';
echo 'PHP_SELF = ' . $_SERVER['PHP_SELF']. '<br>';
echo 'DOCUMENT_ROOT = ' . $_SERVER['DOCUMENT_ROOT']. '<br>';
echo 'SCRIPT_FILENAME = ' . $_SERVER['SCRIPT_FILENAME']. '<br>';
echo 'PATH_TRANSLATED = ' . $_SERVER['PATH_TRANSLATED']. '<br>';
echo 'SCRIPT_NAME = ' . $_SERVER['SCRIPT_NAME']. '<br>';
echo 'PATH_INFO = ' . $_SERVER['PATH_INFO']. '<br>';
echo 'ORIG_PATH_INFO = ' . $_SERVER['ORIG_PATH_INFO']. '<br>';
echo dirname(__FILE__) . "<br>"; */


$router = new Router();

$router->add('/', array(
    'controller'=>'Home',
    'action'=>'index'
    )
);

$router->add('/items/create', array(
    'controller'=>'Items',
    'action'=>'create'
    )
);

/* echo '<pre>';
print_r($router->getRoutes()) . '<br>';
echo '</pre>'; */

$urlParams = explode('/', $url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>$urlParams[1] ?? '',
    'action'=>$urlParams[2] ?? '',
    'params'=>$urlParams[3] ?? ''
);

// var_dump($urlParams);

// Si el controlador está vacío...
// Si no, recoge los valores de controlador y parámetros
if (!empty($urlParams[1])) {
    $urlArray['controller'] = ucwords($urlParams[1]);
    if (!empty($urlParams[2])) {
        $urlArray['action'] = $urlParams[2];
        if(!empty($urlParams[3])) {
            $urlArray['params'] = $urlParams[3];
        }
    } else {
        $urlArray['action'] = 'index';
    }
} else {
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}

if($router->match($urlArray)) {
    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];

    $controller = new $controller();
    $controller->$action();

} else {
    echo "No hay ruta para esa URL: " . $url;
}

echo '<pre>';
print_r($urlArray) . '<br>';
echo '</pre>';