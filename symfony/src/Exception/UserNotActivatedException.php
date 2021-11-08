<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class UserNotActivatedException extends AuthenticationException
{
    // @TODO:: unify order (statuscode, message -> see ApiException)
    public function __construct(string $message = 'User not yet activated', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
