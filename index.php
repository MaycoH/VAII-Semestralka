<?php
session_start();    // Vytvoríme si pole "session"

require "ClassLoader.php";

use App\App;

$app = new App();
$app->run();