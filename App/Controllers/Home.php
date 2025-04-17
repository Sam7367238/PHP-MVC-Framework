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
            $data["name"] = $this -> request -> data("name");
            $data["email"] = $this -> request -> data("email");
        }

        $this -> view("welcome", $data);
    }
}