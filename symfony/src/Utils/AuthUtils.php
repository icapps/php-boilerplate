<?php

namespace App\Utils;

class AuthUtils
{
    /**
     * Get an unique token, ea: activation/reset.
     */
    public static function getUniqueToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
