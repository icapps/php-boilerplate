<?php

namespace App\Repository\Traits;

use http\Exception\InvalidArgumentException;

trait BaseRepositoryFunctionsTrait
{

    /**
     * @return object
     */
    public function create(): object
    {
        $class = $this->getClassName();
        return new $class();
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        $entity = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id
        );

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param object $entity
     */
    public function save(object $entity): void
    {
        $class = $this->getClassName();
        // Check type explicitly
        if (!($entity instanceof $class)) {
            throw new InvalidArgumentException();
        }
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     *
     * @return object|null
     */
    public function findById(int $id): ?object
    {
        $entity = $this->find($id);

        if (!$entity) {
            return null;
        }

        return $entity;
    }

    /**
     * @return object|null
     */
    public function findLatest(): ?object
    {
        return $this->findOneBy([], ['id' => 'DESC']);
    }
}
