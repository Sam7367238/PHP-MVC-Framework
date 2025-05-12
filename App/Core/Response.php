<?php

class Response {
    public static function error($errorType, $code) {
        if (!is_numeric($code)) {
            $code = 500;
        }

        http_response_code($code);
        $filename = "../App/Views/Errors/" . $errorType . ".php";

        if (!file_exists($filename)) {
            echo "<h1>Something Went Wrong</h1>";
            exit();
        }

        require($filename);
        exit();
    }

    public function redirect_to($location = '') {
        header("Location: " . baseUrl() . "/{$location}");
    }

    public function redirect_back() {
        if (isset($_SERVER["HTTP_REFERER"])) {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        } else {
            return Response::error("NotAcceptable", 406);
        }
    }
}