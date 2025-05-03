<?php

class Request {
    public $data = [];
    public $query = [];

    public function __construct() {
        $this -> data = $this -> mergeData($_POST, $_FILES);
        $this -> query = $_GET;
    }

    public function mergeData($post, $files) {
        foreach ($post as $key => $value) {
            if (is_string($value)) {
                $post[$key] = trim($value);
            }
        }

        return array_merge($files, $post);
    }

    public function data($key, $requestType, $filterType) {
        if (!isset($this -> data[$key])) {
            return null;
        }

        switch ($requestType) {
            case "POST": return filter_var($this -> data[$key], $filterType);
            break;

            case "FILE": return $this -> data[$key];
            break;
        }
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
        if (is_array($middleware)) {
            foreach ($middleware as $m) {
                $filePath = "../App/Middleware/" . $m . ".php";

                if (!file_exists($filePath)) {
                    return Response::error("InternalServer", 500);
                }

                require_once($filePath);

                if (class_exists($m)) {
                    new $m;
                } else {
                    return Response::error("InternalServer", 500);
                }
            }
        } else {
            return Response::error("InternalServer", 500);
        }
    }
}