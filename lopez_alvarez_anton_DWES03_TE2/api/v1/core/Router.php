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

    // Los parámetros se sustituyen por expresiones regulares y se matchean
    public function matchRoutes($url) {
        foreach ($this->routes as $route=>$params) {
            $pattern = str_replace(['{id}'], ['([0-9]+)'], $route);
            $pattern = str_replace(['{artist}'], ['([a-z0-9\-]+)'], $pattern);
            $pattern = str_replace(['{format}'], ['([a-z0-9]+)'], $pattern);
            $pattern = str_replace(['{key}'], ['([a-z]+)'], $pattern);
            $pattern = str_replace(['{order}'], ['(asc|desc)'], $pattern);
            $pattern = str_replace(['/'], ['\/'], $pattern);

            $pattern = '/^' . $pattern . '$/i'; // Con la "i" permitimos que los valores sean en mayúsculas o minúsculas

            if (preg_match($pattern, $url['path'])) {
                $this->params = $params;
                return true;
            }
        }

        return false;

    }

    public function getParams() {
        return $this->params;
    }
}