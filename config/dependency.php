<?php

use H22k\CommissionCalculator\App;
use H22k\CommissionCalculator\Bin\Clients\BinListClient;
use H22k\CommissionCalculator\Bin\Contracts\BinClient;
use H22k\CommissionCalculator\CommissionCalculator;
use H22k\CommissionCalculator\Rate\Clients\ExchangeRatesClient;
use H22k\CommissionCalculator\Rate\Contracts\RateClient;
use H22k\CommissionCalculator\Reader\Contracts\ReadStrategy;
use H22k\CommissionCalculator\Reader\Reader;
use H22k\CommissionCalculator\Reader\Strategies\TxtReadStrategy;
use Psr\Container\ContainerInterface;

function getDefaultDependency(ContainerInterface $container, string $dependencyKey)
{
    $defaultValue = $container->get(sprintf('default.%s', $dependencyKey));
    $defaultDependency = $container->get(sprintf('%s.%s.class', $dependencyKey, $defaultValue));
    return $container->get($defaultDependency);
}

$readStrategyCallback = function (ContainerInterface $container) {
    return getDefaultDependency($container, 'reader');
};

$binClientCallback = function (ContainerInterface $container) {
    return getDefaultDependency($container, 'bin');
};

$rateClientCallback = function (ContainerInterface $container) {
    return getDefaultDependency($container, 'rate');
};

return [
    App::class => \DI\autowire(),
    ReadStrategy::class => $readStrategyCallback,
    BinClient::class => $binClientCallback,
    RateClient::class => $rateClientCallback,
    Reader::class => \DI\autowire(),
    TxtReadStrategy::class => \DI\autowire()
        ->constructorParameter('fileName', \DI\get('reader.txt.file')),

    ExchangeRatesClient::class => \DI\autowire()
        ->constructorParameter('requestOption', \DI\get('rate.exchange_rate.options'))
        ->constructorParameter('baseCurrency', \DI\get('app.base.currency'))
        ->constructorParameter('apiAccessKey', \DI\env('EXCHANGE_API_ACCESS_KEY')),

    BinListClient::class => \DI\autowire()->constructorParameter('requestOption', \DI\get('bin.binlist.options')),
    CommissionCalculator::class => \DI\autowire()
        ->constructorParameter('europeanCommissionRate', \DI\get('app.european.commission.rate.float'))
        ->constructorParameter('notEuropeanCommissionRate', \DI\get('app.not.european.commission.rate.float'))
];