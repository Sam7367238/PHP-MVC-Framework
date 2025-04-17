<?php

class Database {
    private static $connection = null;

    public function __construct() {
        if (self::$connection) {
            return;
        }

        $dsn = "mysql:dbname=" . Configuration::get("MySQL/Name") . "; host=" . Configuration::get("MySQL/Host") . "; charset=" . Configuration::get("MySQL/CharSet");
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            self::$connection = new PDO($dsn, Configuration::get("MySQL/User"), Configuration::get("MySQL/Password"), $options);
        } catch(PDOException) {
            return Response::error("ServiceUnavailable", 503);
        }
    }

    public function query($sql, $params = []) {
        if (!self::$connection) {
            return Response::error("ServiceUnavailable", 503);
        }

        $statement = self::$connection -> prepare($sql);
        $statement -> execute($params);

        return $statement;
    }
}