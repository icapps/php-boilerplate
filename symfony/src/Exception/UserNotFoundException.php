<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class UserNotFoundException extends AuthenticationException
{
    public function __construct(string $message = 'User not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
