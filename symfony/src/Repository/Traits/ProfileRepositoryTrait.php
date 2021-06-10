<?php

namespace App\Repository\Traits;

trait ProfileRepositoryTrait
{
    use ProfileRepositoryFunctionsTrait, AbstractRepositoryFunctionsTrait, TransactionalTrait {
        ProfileRepositoryFunctionsTrait::findById insteadof AbstractRepositoryFunctionsTrait;
        ProfileRepositoryFunctionsTrait::findLatest insteadof AbstractRepositoryFunctionsTrait;
    }
}
