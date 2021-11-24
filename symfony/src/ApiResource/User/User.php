<?php

namespace App\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiProperty;

final class User
{
    /**
     * @ApiProperty(identifier=true)
     *
     * The user string identifier.
     */
    public string $userSid;
}
