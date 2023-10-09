<?php

use H22k\CommissionCalculator\App;

define('ROOT_PATH', dirname(__FILE__));

$container = require_once __DIR__ . '/bootstrap/app.php';

$app = $container->get(App::class);
$app->run();
