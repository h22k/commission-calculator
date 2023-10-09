<?php

use H22k\CommissionCalculator\Reader\Strategies\TxtReadStrategy;
use Psr\Container\ContainerInterface;

return [
    'default.reader' => $_ENV['DEFAULT_READER'],
    'root_path' => ROOT_PATH,
    'reader.file' => function (ContainerInterface $container) {
        $fileName = $_SERVER['argv'][1] ?? $container->get('app.default.file');
        return $container->get('root_path') . '/' . $fileName;
    },
    'reader.txt.class' => TxtReadStrategy::class
];
