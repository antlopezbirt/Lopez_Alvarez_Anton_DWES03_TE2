<?php

namespace app\core;

class Router {

    protected $routes = array();

    public function __construct() {
        $this->routes = [
            '/' => 'HomeController@index',
            '/items' => 'ItemController@index',
            '/items/get' => 'ItemController@getAll',
            '/item/get/{id}' => 'ItemController@getById',
            '/item/create' => 'ItemController@create',
            '/item/update' => 'ItemContrller@update',
            '/item/delete' => 'ItemController@delete'
        ];
    }

    public function add($route, $params) {
        $this->routes[$route] = $params;
    }

    public function dispatch($url) {

        // Se carga la parte inicial de la ruta
        $url = str_replace(BASE_URL, '', $url);

        //$url = parse_url($url, PHP_URL_PATH);

        // Comprueba que la url existe en el array de rutas,
        // e instancia el controlador y método correspondientes
        if (array_key_exists($url, $this->routes)) {
            // Guarda en $controller el controlador y en $method el método con explode
            list($controller, $method) = explode('@', $this->routes[$url]);

            // Agrega el namespace al controlador
            $controller = 'app\\controllers\\' . $controller;

            // Si existe dicho controlador y dicho método, instancia el controlador y llama al método
            if(class_exists($controller) && method_exists($controller, $method)) {
                $controllerInstance = new $controller();
                $controllerInstance->$method();
            } else {
                $this->sendNotFound();

            }
        } else {
            $this->sendNotFound();

        }
    }


    private function sendNotFound() {
        //header("HTTP/1.0 404 Not Found");
        http_response_code(404);
        echo "404 Not Found";
    }
}