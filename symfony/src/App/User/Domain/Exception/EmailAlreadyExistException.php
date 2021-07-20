<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class EmailAlreadyExistException extends \InvalidArgumentException implements \Throwable
{
    public const EMAIL_EXISTS_HTTP_STATUS_CODE = 409;

    public function __construct()
    {
        parent::__construct('Email already registered.', self::EMAIL_EXISTS_HTTP_STATUS_CODE);
    }
}
