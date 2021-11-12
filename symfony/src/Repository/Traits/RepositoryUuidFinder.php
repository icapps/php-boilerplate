<?php

namespace App\Repository\Traits;

use App\Entity\Profile;
use App\Entity\User;
use App\Utils\UuidEncoder;
use App\Entity\Device;

trait RepositoryUuidFinder
{
    /**
     * Find entity by encoded UUID.
     *
     * @param string $encodedUuid
     *
     * @return Device|Profile|User|null
     */
    public function findByEncodedUuid(string $encodedUuid): Device|Profile|User|null
    {
        return $this->findOneBy([
            'uuid' => UuidEncoder::decode($encodedUuid)
        ]);
    }
}
