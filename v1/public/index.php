<?php

require '../core/Router.php';
require '../app/controllers/Items.php';

$url = $_SERVER['QUERY_STRING'];
echo 'URL = ' . $url . '<br>';

$router = new Router();

$router->add('/v1/public', array(
    'controller'=>'Home',
    'action'=>'index'
    )
);

$router->add('/v1/public/items/create', array(
    'controller'=>'Items',
    'action'=>'create'
    )
);

echo '<pre>';
print_r($router->getRoutes()) . '<br>';
echo '</pre>';

$urlParams = explode('/', $url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>$urlParams[3],
    'action'=>$urlParams[4],
    'params'=>$urlParams[5]
);

// Si el controlador está vacío...
// Si no, recoge los valores de controlador y parámetros
if (!empty($urlParams[3])) {
    $urlArray['controller'] = ucwords($urlParams[3]);
    if (!empty($urlParams[4])) {
        $urlArray['action'] = $urlParams[4];
        if(!empty($urlParams[5])) {
            $urlArray['params'] = $urlParams[5];
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