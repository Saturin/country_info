<?php

use App\App;

include "vendor/autoload.php";
include "App/App.php";
define("MAINPATH", __DIR__ . "/");

App::render();