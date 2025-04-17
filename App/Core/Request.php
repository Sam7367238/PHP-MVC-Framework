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

    public function data($key) {
        return $this -> data[$key] ?? null;
    }

    public function query($key) {
        return $this -> query[$key] ?? null;
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