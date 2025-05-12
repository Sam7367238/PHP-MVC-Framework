<?php

class Home extends Controller {
    private static $middleware = [];
    private $primaryModel;

    public function __construct($container, $method = null) {
        parent::__construct($container, $method, self::$middleware);

        // $this -> primaryModel = $this -> model();
    }

    public function index() {
        $data = [];

        if ($this -> request -> isPost()) {
            $name = $this -> request -> post("name", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = $this -> request -> post("email", FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($name) || empty($email)) {
                $data["error"] = "All fields are required.";
            } else {
                $data["name"] = $name;
                $data["email"] = $email;
            }
        }

        return $this -> view("welcome", $data);
    }
}