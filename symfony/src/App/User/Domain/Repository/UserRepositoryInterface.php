<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Doctrine\User;

interface UserRepositoryInterface
{
    /**
     * @return User
     */
    public function create(): User;

    /**
     * @param User $user
     */
    public function store(User $user): void;

    /**
     * @param User $user
     */
    public function remove(User $user): void;

    /**
     * @param Email $email
     *
     * @return User|null
     */
    public function findUserByEmail(Email $email): ?User;
}
