<?php

namespace App\Repository\Model;

use App\Component\Model\ProfileInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

/**
 * User Profiles Repositories implementing this interface must have these methods for automation boilerplate
 */
interface AbstractRepositoryFunctionsInterface
{
    /**
     * @return object
     */
    public function create(): object;

    /**
     * @param int $id
     */
    public function remove(int $id): void;

    /**
     * @param object $entity
     */
    public function save(object $entity): void;

    /**
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object;

    /**
     * @return object|null
     */
    public function findLatest(): ?object;
}
