<?php

define('ROOT_PATH', dirname(__FILE__) . '/');

$container = require_once __DIR__ . '/bootstrap/app.php';

$app = $container->get(\H22k\CommissionCalculator\App::class);
$app->run();
