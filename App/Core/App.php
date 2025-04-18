<?php

class App {
    private static $controller = "Home";
    private static $method = "index";
    private static $params = [];

    private static function splitURL()  {
        $url = $_GET["url"] ?? "home";
        $url = trim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return explode('/', $url);
    }

    public static function run() {
        $urlParts = self::splitURL();

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $urlParts[0])) {
            Response::error("BadRequest", 400);
            return;
        }

        $controllerName = ucfirst($urlParts[0]);
        $filename = "../App/Controllers/" . $controllerName . ".php";

        if (!file_exists($filename)) {
            Response::error("NotFound", 404);
            return;
        }

        require($filename);

        if (!class_exists($controllerName)) {
            Response::error("NotFound", 404);
            return;
        }

        self::$controller = $controllerName;
        unset($urlParts[0]);

        $method = $urlParts[1] ?? "index";

        if ($method === "__construct" || !preg_match('/^[a-zA-Z0-9_]+$/', $method)) {
            Response::error("BadRequest", 400);
            return;
        }

        if (method_exists(self::$controller, $method)) {
            self::$method = $method;
            unset($urlParts[1]);
        } elseif (!empty($urlParts[1])) {
            Response::error("NotFound", 404);
            return;
        }

        self::$params = array_values($urlParts);

        $controllerInstance = new self::$controller(new Request(), self::$method);

        try {
            call_user_func_array([$controllerInstance, self::$method], self::$params);
        } catch (ArgumentCountError | TypeError) {
            Response::error("BadRequest", 400);
        }
    }
}
