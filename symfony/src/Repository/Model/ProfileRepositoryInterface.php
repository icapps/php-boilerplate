<?php

namespace App\Repository\Model;

use App\Component\Model\ProfileInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

/**
 * User Profiles Repositories implementing this interface must have these methods for automation boilerplate
 */
interface ProfileRepositoryInterface extends TransactionalInterface
{
    /**
     * @return ProfileInterface
     */
    public function create(): ProfileInterface;

    /**
     * @param int $id
     */
    public function remove(int $id): void;

    /**
     * @param ProfileInterface $profile
     */
    public function save(ProfileInterface $profile): void;

    /**
     * @param int $id
     * @return ProfileInterface|null
     */
    public function findById(int $id): ?ProfileInterface;

    /**
     * @return ProfileInterface|null
     */
    public function findLatest(): ?ProfileInterface;
}
