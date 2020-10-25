<?php

namespace Insurance;

use Insurance\Entities\AllowedProviders;
use Insurance\Services\LogServiceInterface;
use Insurance\Validators\ProvidersValidator;
use Insurance\Exceptions\ValidationNotPassedException;
use Insurance\Factories\ProviderPriceRepositoryFactory;

class Insurance
{
    private ProvidersValidator $validator;
    private AllowedProviders $allowedProviders;
    private ProviderPriceRepositoryFactory $factory;
    private LogServiceInterface $logService;

    public function __construct(
        ProvidersValidator $validator,
        AllowedProviders $allowedProviders,
        ProviderPriceRepositoryFactory $factory,
        LogServiceInterface $logService
    ) {
        $this->validator = $validator;
        $this->allowedProviders = $allowedProviders;
        $this->factory = $factory;
        $this->logService = $logService;
    }

    public function quote(array $providers = []): array
    {
        try {
            if (\count($providers) === 0) {
                $providers = $this->allowedProviders->getProviders();
            }

            if ($this->validator->valid($providers) === false) {
                throw new ValidationNotPassedException("Validation failed");
            }

            $quote = [];

            foreach ($providers as $provider) {
                $quote[$provider] = $this->factory->createByAlias($provider)->get();
            }

            return $quote;
        } catch (\Throwable $exception) {
            $this->logService->log($exception);
            return [];
        }
    }
}
