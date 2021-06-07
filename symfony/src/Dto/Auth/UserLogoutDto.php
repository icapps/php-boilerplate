<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserLogoutDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
final class UserLogoutDto
{
    /**
     * @var string
     *
     * @Groups({"auth:api-write"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceId;
}
