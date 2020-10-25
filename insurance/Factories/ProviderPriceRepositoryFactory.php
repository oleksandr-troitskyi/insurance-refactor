<?php

namespace Insurance\Factories;

use Insurance\Services\LogServiceInterface;
use Insurance\Exceptions\RepositoryNotFoundException;
use Insurance\Repositories\ProviderPriceRepositoryInterface;

class ProviderPriceRepositoryFactory
{
    protected LogServiceInterface $logService;

    public function __construct(LogServiceInterface $logService)
    {
        $this->logService = $logService;
    }

    public function createByAlias(string $alias): ProviderPriceRepositoryInterface
    {
        try {
            $name = '\Insurance\Repositories\\' . $this->transformAlias($alias) . 'ProviderPriceRepository';

            return new $name($this->logService);
        } catch (\Throwable $exception) {
            $this->logService->log($exception);
            throw new RepositoryNotFoundException('Error while creating a repository with alias ' . $alias);
        }
    }

    /**
     * @param string $alias
     * @return string
     */
    protected function transformAlias(string $alias): string
    {
        $aliasName = '';
        $aliasArray = explode('-', $alias);
        foreach ($aliasArray as $item) {
            $aliasName .= \ucfirst($item);
        }
        return $aliasName;
    }
}
