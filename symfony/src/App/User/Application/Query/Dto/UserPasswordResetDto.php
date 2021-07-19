<?php

namespace App\User\Application\Query\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
