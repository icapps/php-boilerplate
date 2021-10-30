<?php

namespace App\Utils;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidEncoder
{
    /**
     * Base64 encode given UUID.
     *
     * @param UuidInterface $uuid
     *
     * @return string
     */
    public static function encode(UuidInterface $uuid): string
    {
        return base64_encode($uuid);
    }

    /**
     * Decode encoded UUID.
     *
     * @param string $encoded
     *
     * @return UuidInterface
     */
    public static function decode(string $encoded): ?UuidInterface
    {
        try {
            return Uuid::fromString(base64_decode($encoded));
        } catch (\Exception $e) {
            return null;
        }
    }
}
