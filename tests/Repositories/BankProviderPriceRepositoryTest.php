<?php

use PHPUnit\Framework\TestCase;
use Insurance\Facades\RequestFacade;
use Insurance\Repositories\BankProviderPriceRepository;
use Insurance\Repositories\ProviderPriceRepositoryInterface;

class BankProviderPriceRepositoryTest extends TestCase
{
    public function testRepositoryImplementsInterface()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $this->assertInstanceOf(ProviderPriceRepositoryInterface::class, new BankProviderPriceRepository($logService));
    }

    public function testRepositoryCallsFacadeWithEndpointNeeded()
    {
        $array = [];

        $facade = $this->createMock(RequestFacade::class);
        $facade->expects($this->once())
            ->method('request')
            ->with($this->equalTo(BankProviderPriceRepository::ENDPOINT_URL))
            ->willReturn($array);
        $repository = $this->createPartialMock(BankProviderPriceRepository::class, ['getFacade']);
        $repository->expects($this->once())->method('getFacade')->willReturn($facade);
        $this->assertEquals([], $repository->get());
    }
}
