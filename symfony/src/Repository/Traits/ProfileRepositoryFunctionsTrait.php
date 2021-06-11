<?php

namespace App\Repository\Traits;

use App\Component\Model\ProfileInterface;

trait ProfileRepositoryFunctionsTrait
{
    /**
     * @return ProfileInterface
     */
    public function create(): ProfileInterface
    {
        $class = $this->getClassName();
        return new $class();
    }

    /**
     * @param int $id
     * @return ProfileInterface|null
     */
    public function findById(int $id): ?ProfileInterface
    {
        $entity = $this->find($id);

        if (!$entity) {
            return null;
        }

        return $entity;
    }

    /**
     * @return ProfileInterface|null
     */
    public function findLatest(): ?ProfileInterface
    {
        return $this->findOneBy([], ['id' => 'DESC']);
    }
}
