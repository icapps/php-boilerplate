<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class InvalidPayloadException extends HttpException
{
    public const INVALID_PAYLOAD_HTTP_STATUS_CODE = 400;

    public function __construct(?string $message, \Throwable $previous = null, array $headers = [])
    {
        parent::__construct(
            self::INVALID_PAYLOAD_HTTP_STATUS_CODE,
            $message,
            $previous,
            $headers,
            self::INVALID_PAYLOAD_HTTP_STATUS_CODE
        );
    }
}
