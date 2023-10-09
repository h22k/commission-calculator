<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..');
$dotenv->load();

$dotenv->required('RATE_DEFAULT_API')->notEmpty();
$dotenv->required('EXCHANGE_API_ACCESS_KEY')->notEmpty();
$dotenv->required('EXCHANGE_API_URI')->notEmpty();
$dotenv->required('BIN_DEFAULT_API')->notEmpty();
$dotenv->required('BINLIST_API_URI')->notEmpty();
$dotenv->required('BASE_CURRENCY')->notEmpty();
$dotenv->required('EUROPEAN_COMMISSION_RATE')->notEmpty();
$dotenv->required('NOT_EUROPEAN_COMMISSION_RATE')->notEmpty();
$dotenv->required('DEFAULT_FILE')->notEmpty();
$dotenv->required('DEFAULT_READER')->notEmpty();
