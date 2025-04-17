<?php

class Authenticated {

    public function __construct() {
        if (!Session::get("User")) {
            return Response::error("Unauthorized", 401);
        }
    }
}