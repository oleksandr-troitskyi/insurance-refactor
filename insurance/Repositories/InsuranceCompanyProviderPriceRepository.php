<?php

namespace Insurance\Repositories;

use Insurance\Facades\RequestFacade;

class InsuranceCompanyProviderPriceRepository extends Repository implements ProviderPriceRepositoryInterface
{
    const ENDPOINT_URL = 'http://demo9084693.mockable.io/insurance';
    const MONTHS = 3;

    public function getFacade(): RequestFacade
    {
        return new RequestFacade($this->logService);
    }

    public function get(): array
    {
        return $this->getFacade()->request(self::ENDPOINT_URL, ['months' => self::MONTHS]);
    }
}
