<?php

use Insurance\Entities\AllowedProviders;
use Insurance\Validators\ProvidersValidator;

class ProvidersValidatorTest extends \PHPUnit\Framework\TestCase
{
    public function testItThrowsErrorWithParamOfWrongType()
    {
        $allowedProviders = $this->createMock(AllowedProviders::class);
        $validator = new ProvidersValidator($allowedProviders);

        $this->expectException('TypeError');
        $validator->valid('string');
    }

    public function testItFailsWithEmptyArray()
    {
        $allowedProviders = $this->createMock(AllowedProviders::class);
        $allowedProviders->expects($this->never())->method('getProviders');
        $validator = new ProvidersValidator($allowedProviders);

        $this->assertFalse($validator->valid([]));
    }

    public function testItFailsWithAliasNotAllowed()
    {
        $allowedProviders = $this->createMock(AllowedProviders::class);
        $allowedProviders->expects($this->once())->method('getProviders')->willReturn(['some', 'providers']);
        $validator = new ProvidersValidator($allowedProviders);

        $this->assertFalse($validator->valid(['different']));
    }

    public function testItPassesWithAliasAllowed()
    {
        $allowedProviders = $this->createMock(AllowedProviders::class);
        $allowedProviders->expects($this->once())->method('getProviders')->willReturn(['some', 'providers']);
        $validator = new ProvidersValidator($allowedProviders);

        $this->assertTrue($validator->valid(['some']));
    }
}