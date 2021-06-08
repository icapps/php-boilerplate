<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserPasswordResetDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
final class UserPasswordResetDto
{
    /**
     * @var string
     *
     * @Groups({"auth:api-post"})
     * @Assert\Email(
     *     message="icapps.registration.email.invalid",
     * )
     * @Assert\NotBlank()
     */
    public string $email;
}
