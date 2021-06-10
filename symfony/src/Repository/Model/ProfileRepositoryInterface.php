<?php

namespace App\Repository\Model;

use App\Component\Model\ProfileInterface;

/**
 * User Profiles Repositories implementing this interface must have these methods for automation boilerplate
 */
interface ProfileRepositoryInterface extends AbstractRepositoryFunctionsInterface, TransactionalInterface
{
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
