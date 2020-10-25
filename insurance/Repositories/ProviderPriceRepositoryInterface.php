<?php

namespace Insurance\Repositories;

use Insurance\Services\LogServiceInterface;

interface ProviderPriceRepositoryInterface
{
    public function __construct(LogServiceInterface $logService);

    public function get(): array;
}
