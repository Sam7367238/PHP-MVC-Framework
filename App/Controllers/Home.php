<?php

class Home extends Controller {
    private static $middleware = [];
    private $primaryModel;

    public function __construct(?Request $request = null, $method = null) {
        parent::__construct($request, $method, self::$middleware);

        // $this -> primaryModel = $this -> model();
    }


    public function index() {
        $data = [];

        if (Request::isPost()) {
            $name = $this -> request -> data("name", "POST", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = $this -> request -> data("email", "POST", FILTER_SANITIZE_SPECIAL_CHARS);

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