<?php

namespace App\Repository\Traits;

use App\Utils\UuidEncoder;

trait RepositoryUuidFinder
{
    /**
     * Find entity by encoded UUID.
     *
     * @param string $encodedUuid
     */
    public function findByEncodedUuid(string $encodedUuid)
    {
        return $this->findOneBy([
            'uuid' => UuidEncoder::decode($encodedUuid)
        ]);
    }
}
