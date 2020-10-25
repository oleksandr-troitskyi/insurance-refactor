<?php

use PHPUnit\Framework\TestCase;
use Insurance\Facades\RequestFacade;
use Insurance\Repositories\ProviderPriceRepositoryInterface;
use Insurance\Repositories\InsuranceCompanyProviderPriceRepository;

class InsuranceCompanyProviderPriceRepositoryTest extends TestCase
{
    public function testRepositoryImplementsInterface()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $this->assertInstanceOf(ProviderPriceRepositoryInterface::class, new InsuranceCompanyProviderPriceRepository($logService));
    }

    public function testRepositoryCallsFacadeWithEndpointNeeded()
    {
        $array = [];

        $facade = $this->createMock(RequestFacade::class);
        $facade->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo(InsuranceCompanyProviderPriceRepository::ENDPOINT_URL),
                $this->equalTo(['months' => InsuranceCompanyProviderPriceRepository::MONTHS])
            )
            ->willReturn($array);
        $repository = $this->createPartialMock(InsuranceCompanyProviderPriceRepository::class, ['getFacade']);
        $repository->expects($this->once())->method('getFacade')->willReturn($facade);
        $this->assertEquals([], $repository->get());
    }
}
