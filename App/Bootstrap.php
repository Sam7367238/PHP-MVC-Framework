<?php

require("Configuration/Main.php");

spl_autoload_register(
    function($classname) {
        require("Core/" . $classname . ".php");
    }
);