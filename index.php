<?php

require __DIR__ . '/vendor/autoload.php';

use Insurance\Insurance;

$allowedProviders = new \Insurance\Entities\AllowedProviders();
$logService = new \Insurance\Services\LogService();
$insurance = new Insurance(
    new \Insurance\Validators\ProvidersValidator($allowedProviders),
    $allowedProviders,
    new \Insurance\Factories\ProviderPriceRepositoryFactory($logService),
    $logService
);
$quote = $insurance->quote([]);

var_dump($quote);
