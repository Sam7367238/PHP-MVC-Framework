<?php

class Authenticated {
    private $session;
    private $response;

    public function __construct($container) {
        $this -> session = $container -> get(Session::class);
        $this -> response = $container -> get(Response::class);
    }

    public function handle() {
        if (!$this -> session -> get("User")) {
            $this -> response -> error("Unauthorized", 401);
        }
    }
}