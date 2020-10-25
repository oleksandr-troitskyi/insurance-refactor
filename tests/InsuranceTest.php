<?php

use Insurance\Entities\AllowedProviders;
use Insurance\Services\LogServiceInterface;
use Insurance\Validators\ProvidersValidator;
use Insurance\Exceptions\ValidationNotPassedException;
use Insurance\Factories\ProviderPriceRepositoryFactory;
use Insurance\Repositories\ProviderPriceRepositoryInterface;

class InsuranceTest extends \PHPUnit\Framework\TestCase
{
    public function testItAcceptsEmptyArray()
    {
        $providers = [];
        $allowedArray = ['one', 'two'];
        $expected = ['one' => [], 'two' => []];

        $validator = $this->createMock(ProvidersValidator::class);
        $validator->expects($this->once())->method('valid')->with($this->equalTo($allowedArray))->willReturn(true);

        $allowedProviders = $this->createMock(AllowedProviders::class);
        $allowedProviders->expects($this->once())->method('getProviders')->willReturn($allowedArray);

        $repository = $this->createMock(\Insurance\Repositories\ProviderPriceRepositoryInterface::class);
        $repository->expects($this->exactly(2))->method('get')->willReturn([]);

        $factory = $this->createMock(ProviderPriceRepositoryFactory::class);
        $factory->expects($this->exactly(2))->method('createByAlias')->withConsecutive(
            [$allowedArray[0]],
            [$allowedArray[1]]
        )->willReturn($repository);

        $logService = $this->createMock(LogServiceInterface::class);
        $logService->expects($this->never())->method('log');

        $insurance = new Insurance\Insurance($validator, $allowedProviders, $factory, $logService);

        $this->assertEquals($expected, $insurance->quote($providers));
    }

    public function testItDoesNotAllowWrongProviders()
    {
        $providers = ['three'];
        $allowedArray = ['one', 'two'];
        $expected = [];

        $validator = $this->createMock(ProvidersValidator::class);
        $validator->expects($this->once())->method('valid')->with($this->equalTo($providers))->willReturn(false);

        $allowedProviders = $this->createMock(AllowedProviders::class);
        $allowedProviders->expects($this->never())->method('getProviders')->willReturn($allowedArray);

        $repository = $this->createMock(ProviderPriceRepositoryInterface::class);
        $repository->expects($this->never())->method('get');

        $factory = $this->createMock(ProviderPriceRepositoryFactory::class);
        $factory->expects($this->never())->method('createByAlias');

        $logService = $this->createMock(LogServiceInterface::class);
        $logService->expects($this->once())->method('log')->with(new ValidationNotPassedException('Validation failed'));

        $insurance = new Insurance\Insurance($validator, $allowedProviders, $factory, $logService);

        $this->assertEquals($expected, $insurance->quote($providers));
    }

    public function testItDoesNotAllowCorrectProviders()
    {
        $providers = ['one'];
        $expected = ['one' => []];

        $validator = $this->createMock(ProvidersValidator::class);
        $validator->expects($this->once())->method('valid')->with($this->equalTo($providers))->willReturn(true);

        $allowedProviders = $this->createMock(AllowedProviders::class);
        $allowedProviders->expects($this->never())->method('getProviders');

        $repository = $this->createMock(ProviderPriceRepositoryInterface::class);
        $repository->expects($this->once())->method('get')->willReturn([]);

        $factory = $this->createMock(ProviderPriceRepositoryFactory::class);
        $factory->expects($this->once())->method('createByAlias')->with($providers[0])->willReturn($repository);

        $logService = $this->createMock(LogServiceInterface::class);
        $logService->expects($this->never())->method('log');

        $insurance = new Insurance\Insurance($validator, $allowedProviders, $factory, $logService);

        $this->assertEquals($expected, $insurance->quote($providers));
    }
}
