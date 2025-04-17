<?php

class Controller {
    protected $request;

    public function __construct(?Request $request = null, $method = null, $middleware = []) {
        $this -> request = $request ?? new Request();

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

        return Response::error("InternalServer", 500);
    }

    public function middleware($method, $middlewareMap) {
        if (!is_array($middlewareMap)) {
            return Response::error("InternalServer", 500);
        }

        if (isset($middlewareMap[$method])) {
            return Request::middleware($middlewareMap[$method]);
        }
    }
}