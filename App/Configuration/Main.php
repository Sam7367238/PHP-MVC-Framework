<?php

function baseUrl() {
    return Configuration::get("Application/BaseURL");
}

$GLOBALS["config"] = [
    "MySQL" => [
        "Host" => "localhost",
        "User" => "root",
        "Password" => "Ayman_Database",
        "Name" => "MainDatabase",
        "CharSet" => "utf8"
    ],

    "Application" => [
        "BaseURL" => "http://localhost/PHPMVCFramework"
    ]
];