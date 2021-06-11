<?php

namespace App\Repository\Traits;

trait ProfileRepositoryTrait
{
    use ProfileRepositoryFunctionsTrait, BaseRepositoryFunctionsTrait, TransactionalTrait {
        ProfileRepositoryFunctionsTrait::create insteadof BaseRepositoryFunctionsTrait;
        ProfileRepositoryFunctionsTrait::findById insteadof BaseRepositoryFunctionsTrait;
        ProfileRepositoryFunctionsTrait::findLatest insteadof BaseRepositoryFunctionsTrait;
    }
}
