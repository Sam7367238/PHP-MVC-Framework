<?php

require("../App/Bootstrap.php");

$app = new App();

Session::init();
$app -> run();