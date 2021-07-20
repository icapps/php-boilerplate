<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenException extends HttpException
{
    public const FORBIDDEN_HTTP_STATUS_CODE = 403;

    public function __construct(?string $message, \Throwable $previous = null, array $headers = [])
    {
        parent::__construct(
            self::FORBIDDEN_HTTP_STATUS_CODE,
            $message,
            $previous,
            $headers,
            self::FORBIDDEN_HTTP_STATUS_CODE
        );
    }
}
