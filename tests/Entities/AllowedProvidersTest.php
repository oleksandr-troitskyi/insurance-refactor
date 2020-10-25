<?php

class AllowedProvidersTest extends \PHPUnit\Framework\TestCase
{
    public function testItReturnsArrayWithProviders()
    {
        $providers = new \Insurance\Entities\AllowedProviders();
        $this->assertEquals(['bank', 'insurance-company'], $providers->getProviders());
    }
}
