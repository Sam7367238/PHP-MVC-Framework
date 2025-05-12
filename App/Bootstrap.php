<?php

require("Configuration/Main.php");
require("Core/Container.php");

spl_autoload_register(
    function($classname) {
        require("Core/" . $classname . ".php");
    }
);

$container = new Container();

$container -> set(Request::class, function() use ($container) {
    return new Request($container -> get(Response::class));
});

$container -> set(Database::class, function() {
    return new Database();
});

$container -> set(Response::class, function () {
    return new Response();
});

$container -> set(Session::class, function () {
    Session::init();
    return new Session();
});

$container -> set(App::class, function() use ($container) {
    return new App($container);
});