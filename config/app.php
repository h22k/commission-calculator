<?php

use Psr\Container\ContainerInterface;

$floatEuropeanCommissionRateCallback = function (ContainerInterface $container) {
    return (float) $container->get('app.european.commission.rate');
};

$floatNotEuropeanCommissionRateCallback = function (ContainerInterface $container) {
    return (float) $container->get('app.not.european.commission.rate');
};

return [
    'app.default.file' => \DI\env('DEFAULT_FILE'),
    'app.base.currency' => \DI\env('BASE_CURRENCY'),
    'app.european.commission.rate' => \DI\env('EUROPEAN_COMMISSION_RATE'),
    'app.european.commission.rate.float' => $floatEuropeanCommissionRateCallback,
    'app.not.european.commission.rate' => \DI\env('NOT_EUROPEAN_COMMISSION_RATE'),
    'app.not.european.commission.rate.float' => $floatNotEuropeanCommissionRateCallback,
];