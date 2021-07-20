<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class InvalidCredentialsException extends \InvalidArgumentException implements \Throwable
{
    public const INVALID_CREDENTIALS_HTTP_STATUS_CODE = 401;

    public function __construct()
    {
        parent::__construct('Invalid credentials.', self::INVALID_CREDENTIALS_HTTP_STATUS_CODE);
    }
}
