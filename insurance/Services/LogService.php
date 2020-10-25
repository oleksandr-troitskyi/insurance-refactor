<?php

namespace Insurance\Services;

class LogService implements LogServiceInterface
{
    public function log(\Throwable $exception): void
    {
        print_r($exception->getCode() . ': ' . $exception->getMessage());
    }
}
