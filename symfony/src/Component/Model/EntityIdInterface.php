<?php

namespace App\Component\Model;

use Ramsey\Uuid\UuidInterface;

/**
 * Trait implements combination of auto incremental primary key (database) and UUID for unique primary identity.
 */
interface EntityIdInterface
{
    /**
     * Returns primary key (database) of object.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Returns UUID of object.
     *
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface;
}
