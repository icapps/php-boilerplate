<?php

namespace App\Exception;

use ApiPlatform\Core\Exception\ExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiHttpException extends HttpException implements ExceptionInterface
{
    /**
     * @param string $message
     *   The detailed error message.
     * @param int $statusCode
     *   The HTTP status code.
     * @param array $headers
     *   Headers with additional info, example: custom internal response code.
     */
    public function __construct(string $message, int $statusCode, array $headers = [])
    {
        parent::__construct($statusCode, $message, null, $headers, $statusCode);
    }
}
