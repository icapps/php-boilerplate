<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AuthAccessDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 */
final class AuthAccessDto
{
    // Used in Swagger alterations.
    public const AUTH_ROUTE_PREFIX = '/auth';
    public const AUTH_LOGIN_URL = '/api/auth/login';
    public const AUTH_ME_URL = '/api/auth/me';
    public const AUTH_BUNDLE_TAG = 'Authentication';

    /**
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public string $token;

    /**
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank
     */
    public string $refreshToken;
}
