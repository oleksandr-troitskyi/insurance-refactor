<?php

namespace Insurance\Entities;

class AllowedProviders
{
    private array $providers;

    public function __construct()
    {
        $this->providers = ['bank', 'insurance-company'];
    }

    public function getProviders(): array
    {
        return $this->providers;
    }
}
