<?php

class Request {
    public $data = [];
    public $query = [];

    public function __construct() {
        $this -> query = $_GET;
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

    public static function isPost() {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    public static function isGet() {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }

    public static function middleware($middleware) {
        if (!is_array($middleware)) {
            return Response::error("InternalServer", 500);
        }
        
        foreach ($middleware as $m) {
            $filePath = "../App/Middleware/" . $m . ".php";

            if (!file_exists($filePath)) {
                return Response::error("InternalServer", 500);
            }

            require_once($filePath);

            if (!class_exists($m)) {
                return Response::error("InternalServer", 500);
            }

            new $m;
        }
    }
}