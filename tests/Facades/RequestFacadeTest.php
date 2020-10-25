<?php

use PHPUnit\Framework\TestCase;
use Insurance\Facades\RequestFacade;
use Insurance\Repositories\BankProviderPriceRepository;

class RequestFacadeTest extends TestCase
{
    public function testItReturnsEmptyArrayForCorrectURL()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $logService->expects($this->once())->method('log');
        $facade = new RequestFacade($logService);
        $this->assertEquals([], $facade->request(BankProviderPriceRepository::ENDPOINT_URL));
    }

    public function testItReturnsEmptyArrayForWrongURL()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $logService->expects($this->exactly(2))->method('log');
        $facade = new RequestFacade($logService);
        $this->assertEquals(
            [],
            $facade->request(
                BankProviderPriceRepository::ENDPOINT_URL . '123123'
            )
        );
        $this->assertEquals(
            [],
            $facade->request(
                BankProviderPriceRepository::ENDPOINT_URL,
                [],
                0
            )
        );
    }

    public function testItLogsAndReturnEmptyErrorOnException()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $logService->expects($this->once())->method('log');
        $facade = new RequestFacade($logService);
        $this->assertEquals(
            [],
            $facade->request(
                '',
                [],
                1
            )
        );
    }

    public function testItReturnsArray()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $logService->expects($this->never())->method('log');
        $facade = new RequestFacade($logService);
        $this->assertEquals(
            [45, 35, 53],
            $facade->request(
                'https://demo5178176.mockable.io/bank',
                []
            )
        );
    }

    public function testItReturnsArrayWithLogging()
    {
        $logService = $this->createMock(\Insurance\Services\LogServiceInterface::class);
        $logService->expects($this->once())->method('log');
        $facade = new RequestFacade($logService);
        $this->assertEquals(
            [],
            $facade->request(
                'https://demo5178176.mockable.io/bankEmpty',
                []
            )
        );
    }
}
