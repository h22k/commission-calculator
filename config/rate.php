<?php

use H22k\CommissionCalculator\RequestOption;

return [
    'default.rate' => \DI\env('RATE_DEFAULT_API'),
    'rate.exchange_rate.class' => \H22k\CommissionCalculator\Rate\Clients\ExchangeRatesClient::class,
    'rate.exchange_rate.options' => \DI\create(RequestOption::class)
        ->constructor('GET', \DI\env('EXCHANGE_API_URI'))
];
