<?php

class Controller {
    protected $container;
    protected $request;

    public function __construct($container, $method = null, $middleware = []) {
        $this -> container = $container;

        $this -> request = $this -> container -> get(Request::class);

        if ($method) {
            $this -> middleware($method, $middleware);
        }
    }

    public function view($view, $data = []) {
        if (is_array($data)) {
            extract($data);
        }

        $filename = "../App/Views/" . $view . ".php";

        if (file_exists($filename)) {
            require($filename);
        }
    }

    public function model($model) {
        $filename = "../App/Models/" . ucfirst($model) . ".php";

        if (file_exists($filename)) {
            require($filename);
            return new $model();
        }

        return $this -> container -> get(Response::class) -> error("InternalServer", 500);
    }

    public function middleware($method, $middlewareMap) {
        if (!is_array($middlewareMap)) {
            return $this -> container -> get(Response::class) -> error("InternalServer", 500);
        }

        if (isset($middlewareMap[$method])) {
            return $this -> container -> get(Request::class) -> middleware($middlewareMap[$method]);
        }
    }
}