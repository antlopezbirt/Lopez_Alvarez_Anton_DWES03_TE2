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


    public function matchRoutes($url) {
        foreach ($this->routes as $route=>$params) {
            $pattern = str_replace(['{id}'], ['([0-9]+)'], $route);
            $pattern = str_replace(['{artist}'], ['([A-Za-z0-9\-]+)'], $pattern);
            $pattern = str_replace(['{format}'], ['([A-Za-z0-9]+)'], $pattern);
            $pattern = str_replace(['{key}'], ['([A-Za-z]+)'], $pattern);
            $pattern = str_replace(['{order}'], ['(asc|desc)'], $pattern);
            $pattern = str_replace(['/'], ['\/'], $pattern);

            $pattern = '/^' . $pattern . '$/';

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