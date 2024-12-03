<?php

class Router {

    protected $routes = array();
    protected $params = array();

    public function add($route, $params) {
        $this->routes[$route] = $params;
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function match($url) {
        foreach($this->routes as $route=>$params) {
            if($url['path'] == $route) {
                if($url['controller'] == $params['controller'] && 
                $url['action'] == $params['action']) {
                    $this->params = $params;
                    return true;
                }
            }
        }
        // Si no coincide con ninguna ruta, devuelve false
        return false;
    }

    public function getParams() {
        return $this->params;
    }
}