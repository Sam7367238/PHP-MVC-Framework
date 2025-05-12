<?php

class Request {
    public $data = [];
    public $query = [];
    protected $response;

    public function __construct($response) {
        $this -> query = $_GET;
        $this -> response = $response;
    }

    public function post($key, $filterType) {
        if (!isset($_POST[$key])) {
            return null;
        }

        return filter_var($_POST[$key], $filterType);
    }

    public function file($key) {
        if (!isset($_FILES[$key])) {
            return null;
        }

        return $_FILES[$key];
    }

    public function query($key, $filterType) {
        if (!isset($this -> query[$key])) {
            return null;
        }

        return filter_var($this -> query[$key], $filterType);
    }

    public function isPost() {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    public function isGet() {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }

    public function middleware($middleware) {
        if (!is_array($middleware)) {
            return $this -> response -> error("InternalServer", 500);
        }
        
        foreach ($middleware as $m) {
            $filePath = "../App/Middleware/" . $m . ".php";

            if (!file_exists($filePath)) {
                return $this -> response -> error("InternalServer", 500);
            }

            require_once($filePath);

            if (!class_exists($m)) {
                return $this -> response -> error("InternalServer", 500);
            }

            $middlewareInstance = new $m($this -> response -> container ?? null);

            if (!method_exists($middlewareInstance, "handle")) {
                return $this -> response -> error("InternalServer", 500);
            }
            
            $middlewareInstance -> handle();
        }
    }
}