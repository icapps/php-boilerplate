<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AuthAccessDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
final class AuthAccessDto
{
    public const AUTH_ROUTE_PREFIX = '/auth';
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
