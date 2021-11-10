<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class ApiException extends HttpException
{
    public function __construct(int $statusCode, string $message, \Exception $previous = null, array $headers = array(), int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
