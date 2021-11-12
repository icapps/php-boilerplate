<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserInfoDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 */
final class UserInfoDto
{
    /**
     * @var string
     *
     * @Groups({"api-get", "user:api-get"})
     *
     * @Assert\NotBlank();
     *
     * @Assert\Unique();
     */
    public string $userSid;

    /**
     * @var string
     *
     * @Groups({"api-get", "user:api-get"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     message="icapps.registration.email.invalid",
     * )
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.email.min_length",
     *     maxMessage="icapps.registration.email.max_length"
     * )
     */
    public string $email;
}
