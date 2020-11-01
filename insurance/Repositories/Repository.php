<?php

namespace Insurance\Repositories;

use Insurance\Services\LogServiceInterface;

abstract class Repository
{
    protected LogServiceInterface $logService;

    public function __construct(LogServiceInterface $logService)
    {
        $this->logService = $logService;
    }
}
