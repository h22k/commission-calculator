<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/dotenv.php';

$configs = glob(ROOT_PATH . '/config/*.php');

$definitions = [];

array_map(function ($configFile) use (&$definitions) {
    $definitions = [...$definitions, ...(require_once $configFile)];
}, $configs);

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions($definitions);
$container = $builder->build();

return $container;