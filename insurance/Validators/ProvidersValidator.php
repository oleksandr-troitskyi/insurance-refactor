<?php

namespace Insurance\Validators;

use Insurance\Entities\AllowedProviders;

class ProvidersValidator
{
    private AllowedProviders $allowedProviders;
    private array $errors = [];

    public function __construct(AllowedProviders $allowedProviders)
    {
        $this->allowedProviders = $allowedProviders;
    }

    public function valid(array $providers = []): bool
    {
        if (\count($providers) === 0) {
            $this->errors[] = 'No providers Presented';
            return false;
        }

        foreach ($providers as $provider) {
            if (!\is_string($provider)) {
                $this->errors[] = 'Provider ' . $provider . 'is not a string';
                continue;
            }
            if (!\in_array($provider, $this->allowedProviders->getProviders())) {
                $this->errors[] = 'Provider ' . $provider . 'is not allowed';
                continue;
            }
        }

        return \count($this->errors) === 0;
    }
}
