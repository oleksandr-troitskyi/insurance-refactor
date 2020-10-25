<?php

namespace Insurance\Facades;

use Insurance\Services\LogServiceInterface;
use Insurance\Exceptions\WrongReturnValueException;

class RequestFacade
{
    const CONNECTION_TIMEOUT_SECONDS = 2;
    const EXECUTION_TIMEOUT_SECONDS = 5;
    const EXPECT_STRING_AS_RETURN_VALUE = 1;
    const REQUEST_POST_TYPE = 1;

    protected LogServiceInterface $logService;

    public function __construct(LogServiceInterface $logService)
    {
        $this->logService = $logService;
    }

    public function request(string $url, array $params = [], string $type = null): array
    {
        try {
            $curl = \curl_init();
            \curl_setopt_array(
                $curl,
                array(
                    CURLOPT_CONNECTTIMEOUT => self::CONNECTION_TIMEOUT_SECONDS,
                    CURLOPT_TIMEOUT => self::EXECUTION_TIMEOUT_SECONDS,
                    CURLOPT_RETURNTRANSFER => self::EXPECT_STRING_AS_RETURN_VALUE,
                    CURLOPT_URL => $url,
                    CURLOPT_POST => $type ?? self::REQUEST_POST_TYPE,
                    CURLOPT_POSTFIELDS => $params
                )
            );
            $result = \json_decode(\curl_exec($curl));
            \curl_close($curl);

            if (\json_last_error() !== JSON_ERROR_NONE) {
                throw new WrongReturnValueException('Error parsing json');
            }

            if (!is_array($result)) {
                throw new WrongReturnValueException('Returned result is not an array');
            }

            return $result;
        } catch (\Throwable $exception) {
            $this->logService->log($exception);

            return [];
        }
    }
}
