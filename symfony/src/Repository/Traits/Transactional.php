<?php

namespace App\Repository\Traits;

trait Transactional
{

    public function beginTransaction(): void
    {
        $this->getEntityManager()->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getEntityManager()->getConnection()->commit();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->getConnection()->rollBack();
    }
}
