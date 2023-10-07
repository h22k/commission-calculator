<?php

use H22k\CommissionCalculator\Reader\Strategies\TxtReadStrategy;
use Psr\Container\ContainerInterface;

return [
    'default.reader' => $_ENV['DEFAULT_READER'],
    'reader.txt.class' => TxtReadStrategy::class,
    'reader.txt.file' => function (ContainerInterface $container) {
        return $_SERVER['argv'][1] ?? $container->get('app.default.file');
    }
];
