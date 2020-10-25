<?php

use Insurance\Services\LogServiceInterface;
use Insurance\Exceptions\RepositoryNotFoundException;
use Insurance\Factories\ProviderPriceRepositoryFactory;
use Insurance\Repositories\BankProviderPriceRepository;
use Insurance\Repositories\InsuranceCompanyProviderPriceRepository;

class ProviderPriceRepositoryFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testItFailsWithWrongAlias()
    {
        $logService = $this->createMock(LogServiceInterface::class);
        $logService->expects($this->once())->method('log');

        $factory = new ProviderPriceRepositoryFactory($logService);
        $alias = 'blank';

        $this->expectException(RepositoryNotFoundException::class);
        $factory->createByAlias($alias);
    }

    public function testItReturnsBankRepository()
    {
        $logService = $this->createMock(LogServiceInterface::class);
        $factory = new ProviderPriceRepositoryFactory($logService);
        $alias = 'bank';

        $this->assertInstanceOf(
            BankProviderPriceRepository::class,
            $factory->createByAlias($alias)
        );
    }

    public function testItReturnsInsuranceCompanyRepository()
    {
        $logService = $this->createMock(LogServiceInterface::class);
        $factory = new ProviderPriceRepositoryFactory($logService);
        $alias = 'insurance-company';

        $this->assertInstanceOf(
            InsuranceCompanyProviderPriceRepository::class,
            $factory->createByAlias($alias)
        );
    }
}
