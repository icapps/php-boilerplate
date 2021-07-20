<?php

declare(strict_types=1);

namespace App\User\Application\Command\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class PasswordResetInput
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
