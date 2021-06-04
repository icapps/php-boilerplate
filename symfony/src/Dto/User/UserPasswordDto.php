<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserPasswordDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class UserPasswordDto
{

    /**
     * @var string
     *
     * @Groups({"api-post", "password:api-post"})
     *
     * @Assert\NotBlank()
     * @Assert\NotCompromisedPassword()
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.password.min_length",
     *     maxMessage="icapps.registration.password.max_length"
     * )
     */
    public string $oldPassword;

    /**
     * @var string
     *
     * @Groups({"api-get", "password:api-get", "password:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.password.min_length",
     *     maxMessage="icapps.registration.password.max_length"
     * )
     */
    public string $password;
}
