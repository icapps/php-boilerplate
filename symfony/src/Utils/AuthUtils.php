<?php

namespace App\Utils;

class AuthUtils
{
    /**
     * Get an unique token, ea: activation/reset.
     */
    public static function getUniqueToken(int $length = 32): string
    {
        return rtrim(strtr(base64_encode(random_bytes($length)), '+/', '-_'), '=');
    }
}
