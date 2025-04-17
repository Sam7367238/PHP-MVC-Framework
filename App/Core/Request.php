<?php

use function PHPSTORM_META\type;

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

    public function data($key, $post, $type = null) {
        if (!isset($this -> data[$key])) {
            return null;
        }

        if (!$post) {
            return $this -> data[$key];
        }

        if ($type == "string") {
            return filter_var($this -> data[$key], FILTER_SANITIZE_SPECIAL_CHARS);
        }

        if ($type == "int") {
            return filter_var($this -> data[$key], FILTER_SANITIZE_NUMBER_INT);
        }
    }

    public function query($key, $type) {
        if (!isset($this -> query[$key])) {
            return null;
        }

        if ($type == "int") {
            return filter_var($this -> query[$key], FILTER_SANITIZE_NUMBER_INT);
        }

        if ($type == "string") {
            return filter_var($this -> query[$key], FILTER_SANITIZE_SPECIAL_CHARS);
        }
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