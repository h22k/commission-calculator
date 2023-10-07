<?php

namespace H22k\CommissionCalculator\Exception\Reader;

class FileNotFoundException extends \Exception
{
    public function __construct(string $fileName, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf("%s is not found!", $fileName), $code, $previous);
    }
}