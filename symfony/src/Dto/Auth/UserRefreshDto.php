<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserRefreshDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
final class UserRefreshDto
{
    /**
     * @var string
     *
     * @Groups({"auth:api-post"})
     *
     * @Assert\NotBlank()
     */
    public string $refreshToken;
}
