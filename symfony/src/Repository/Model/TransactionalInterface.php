<?php

namespace App\Repository\Model;

use App\Component\Model\ProfileInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

/**
 * User Profiles Repositories implementing this interface must have these methods for automation boilerplate
 */
interface TransactionalInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
