<?php

declare(strict_types=1);

namespace App\User\Application\Command\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class LogoutInput
{
    /**
     * @var string
     *
     * @Groups({"auth:api-post"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceId;
}
