<?php

class App {
    public $controller = "Home";
    public $method = "index";
    public $params = [];

    public function splitURL()  {
        $url = $_GET["url"] ?? "home";
        $url = trim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return explode('/', $url);
    }

    public function run() {
        $urlParts = $this -> splitURL();

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

        $this -> controller = $controllerName;
        unset($urlParts[0]);

        $method = $urlParts[1] ?? "index";

        if (method_exists($this -> controller, $method)) {
            $this -> method = $method;
            unset($urlParts[1]);
        } elseif (!empty($urlParts[1])) {
            Response::error("NotFound", 404);
            return;
        }

        $this -> params = array_values($urlParts);

        $controllerInstance = new $this -> controller(new Request(), $this -> method);

        try {
            call_user_func_array([$controllerInstance, $this -> method], $this -> params);
        } catch (ArgumentCountError | TypeError) {
            Response::error("BadRequest", 400);
        }
    }
}
