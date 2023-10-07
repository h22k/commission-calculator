<?php

use H22k\CommissionCalculator\Bin\Clients\BinListClient;
use H22k\CommissionCalculator\RequestOption;

return [
    'default.bin' => $_ENV['BIN_DEFAULT_API'],
    'bin.binlist.class' => BinListClient::class,
    'bin.binlist.options' => \DI\create(RequestOption::class)
        ->constructor('GET', \DI\env('BINLIST_API_URI'))
];