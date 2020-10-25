<?php

require __DIR__ . '/vendor/autoload.php';

use InsuranceNew\Insurance;

$allowedProviders = new \InsuranceNew\Entities\AllowedProviders();
$logService = new \InsuranceNew\Services\LogService();
$insurance = new Insurance(
    new \InsuranceNew\Validators\ProvidersValidator($allowedProviders),
    $allowedProviders,
    new \InsuranceNew\Factories\ProviderPriceRepositoryFactory($logService),
    $logService
);
$quote = $insurance->quote(['bank']);

var_dump($quote);
