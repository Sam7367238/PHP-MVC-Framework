<?php

class App {
    private static $controller = "Home";
    private static $method = "index";
    private static $params;

    private static function splitURL() {
        $URL = $_GET["url"] ?? "home";
        $URL = explode('/', filter_var(trim($URL, '/')), FILTER_SANITIZE_URL);
        return $URL;
    }

    public static function run() {
        $URL = self::splitURL();
    
        $filename = "../App/Controllers/" . ucfirst($URL[0]) . ".php";
        if (file_exists($filename)) {
            require($filename);
            self::$controller = ucfirst($URL[0]);
            unset($URL[0]);
        } else {
            return Response::error("NotFound", 404);
        }
    
        if (!empty($URL[1]) && method_exists(self::$controller, $URL[1]) && $URL[1] !== "__construct") {
            self::$method = $URL[1];
            unset($URL[1]);
        } else if (!empty($URL[1]) && $URL[1] === "__construct") {
            return Response::error("BadRequest", 400);
        }
    
        self::$params = array_values($URL);

        $controller = new self::$controller(new Request(), self::$method);
    
        try {
            call_user_func_array([$controller, self::$method], self::$params);
        } catch (ArgumentCountError) {
            return Response::error("BadRequest", 400);
        } catch (TypeError) {
            return Response::error("BadRequest", 400);
        }
    }
}