<?php

require '../config/config.php';
require '../core/Router.php';
require '../app/controllers/Item.php';
require '../app/models/ItemModel.php';
require '../utils/JSONFileUtil.php';
require '../utils/Response.php';

$url = $_SERVER['QUERY_STRING'];

// Si la query contiene espacios no es válida, devolvemos el error
if (str_contains(urldecode($url), ' ')) {
    $res = new Response(403, 'URL no válida', 'ERROR: La URL no puede contener espacios: ' . urldecode($url));
    $res->enviar();

    die();
}

$url = urldecode($url);


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


$urlParams = explode('/', $url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=> array()
);


// Si el controlador no está vacío, recoge los valores del controlador y parámetros
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
    // En caso de que no haya controlador, se devuelve un 404.
    $res = new Response(404, 'No se encontró ruta para esa URL: ' . $url);
    $res->enviar();
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

    try {
        // Tanto el controlador a instanciar como el método a invocar deben existir
        if (class_exists($controller) && method_exists($controller, $action)) {

            $controller = new $controller();
            $resp = call_user_func_array([$controller, $action], $params);

        } else {
            // Si el controlador o el método no existen, se envía un error 500 con ese mensaje.
            $res = new Response(500, 'Controlador o método no definidos', 'El controlador o el método no son válidos');
            $res->enviar();
        }

    } catch (Exception $e) {
        // En caso de que no se produzca una excepción se devuelve un error 500 con el mensaje.
        $res = new Response(500, 'Error en el servidor', $e->getMessage());
        $res->enviar();
    }

} else {
    // En caso de que no se haga match, se devuelve un 404.
    $res = new Response(404, 'Enrutamiento', 'ERROR: No se encontró ruta para esa URL: ' . $url);
    $res->enviar();
}