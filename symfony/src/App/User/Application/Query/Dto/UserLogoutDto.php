<?php

namespace App\User\Application\Query\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UserLogoutDto
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
