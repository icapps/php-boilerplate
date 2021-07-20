<?php

declare(strict_types=1);

namespace App\User\Application\Command\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

final class RefreshTokenInput
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
