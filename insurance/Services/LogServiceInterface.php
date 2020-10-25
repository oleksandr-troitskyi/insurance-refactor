<?php

namespace Insurance\Services;

interface LogServiceInterface
{
    public function log(\Throwable $exception): void;
}