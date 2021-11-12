<?php

namespace App\Component\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Trait implements combination of auto incremental primary key (database) and UUID for unique primary identity.
 */
trait EntityIdTrait
{
    /**
     * The unique auto incremented primary key.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private int $id;

    /**
     * The internal primary identity key.
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
