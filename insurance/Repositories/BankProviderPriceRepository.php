<?php

namespace Insurance\Repositories;

use Insurance\Facades\RequestFacade;

class BankProviderPriceRepository extends Repository implements ProviderPriceRepositoryInterface
{
    const ENDPOINT_URL = 'http://demo9084693.mockable.io/bank';

    protected function getFacade(): RequestFacade
    {
        return new RequestFacade($this->logService);
    }

    public function get(): array
    {
        return $this->getFacade()->request(self::ENDPOINT_URL);
    }
}
