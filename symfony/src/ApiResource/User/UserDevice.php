<?php

namespace App\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiProperty;

final class UserDevice
{
    /**
     * @ApiProperty(identifier=true)
     *
     * The device string identifier.
     */
    public string $deviceSid;
}
