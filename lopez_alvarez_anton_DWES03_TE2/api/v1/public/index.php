<?php

require '../config/config.php';
require '../core/Router.php';
require '../app/controllers/Item.php';
require '../app/models/ItemModel.php';
require '../app/helpers/Response.php';

$url = $_SERVER['QUERY_STRING'];
//echo 'QUERY_STRING = ' . $url . '<br>';

$router = new Router();

// Rutas GET

$router->add('/items', array(
    'controller'=>'Item',
    'action'=>'getAllItems'
    )
);

$router->add('/item/{id}', array(
    'controller'=>'Item',
    'action'=>'getItemById'
    )
);

$router->add('/items/artist/{artist}', array(
    'controller'=>'Item',
    'action'=>'getItemsByArtist'
    )
);

$router->add('/items/format/{format}', array(
    'controller'=>'Item',
    'action'=>'getItemsByFormat'
    )
);

$router->add('/items/order/{key}/{order}', array(
    'controller'=>'Item',
    'action'=>'sortItemsByKey'
    )
);


// Rutas POST (Create, Put, Delete)

$router->add('/item/create', array(
    'controller'=>'Item',
    'action'=>'createItem'
    )
);

$router->add('/item/update/{id}', array(
    'controller'=>'Item',
    'action'=>'updateItem'
    )
);

$router->add('/item/delete/{id}', array(
    'controller'=>'Item',
    'action'=>'deleteItem'
    )
);


/* echo '<pre>';
print_r($router->getRoutes()) . '<br>';
echo '</pre>'; */

$urlParams = explode('/', $url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=> array()
);

//$urlArray['action'] = is_numeric($urlParams[2]) ? '' : $urlParams[2];
//$urlArray[]

// var_dump($urlParams);

// Si el controlador está vacío...
// Si no, recoge los valores de controlador y parámetros
if (!empty($urlParams[1])) {
    $urlArray['controller'] = ucwords($urlParams[1]);
    if (!empty($urlParams[2])) {

        if (is_numeric($urlParams[2])) {
            $urlArray['params'][] = $urlParams[2];
        } else $urlArray['action'] = $urlParams[2];

        if(!empty($urlParams[3])) {
            $urlArray['params'][] = $urlParams[3];
            
            if (!empty($urlParams[4])) $urlArray['params'][] = $urlParams[4];

        }
    } else {
        $urlArray['action'] = 'index';
    }
} else {
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}


$params = [];

if ($router->matchRoutes($urlArray)) {

    if ($urlArray['HTTP'] === 'GET') {
        $params = $urlArray['params'] ?? null;
    } elseif ($urlArray['HTTP'] === 'POST') {
        $json = file_get_contents('php://input');
        $params[] = json_decode($json, true);
    } elseif ($urlArray['HTTP'] === 'PUT') {
        $id = intval($urlArray['params'][0]) ?? null;
        $json = file_get_contents('php://input');
        $params[] = $id;
        $params[] = json_decode($json, true);
    } elseif ($urlArray['HTTP'] === 'DELETE') {
        $params = $urlArray['params'] ?? null;
    }
    
    
    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];
    
    $controller = new $controller();
    
    if (method_exists($controller, $action)) {
        $resp = call_user_func_array([$controller, $action], $params);
    } else {
        echo "El método no existe";
    }
} else {
    // En caso de que no se haga match, se devuelve un 404.

    http_response_code(404);
    $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'No se encontró ruta para esa URL: ' . $url];
    echo json_encode($res);
}