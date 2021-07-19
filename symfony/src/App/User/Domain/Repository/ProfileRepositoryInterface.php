<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Infrastructure\Doctrine\Profile;

interface ProfileRepositoryInterface
{
    /**
     * @return Profile
     */
    public function create(): Profile;

    /**
     * @param Profile $profile
     */
    public function store(Profile $profile): void;

    /**
     * @param Profile $profile
     */
    public function remove(Profile $profile): void;
}
