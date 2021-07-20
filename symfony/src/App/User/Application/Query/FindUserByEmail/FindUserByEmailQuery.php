<?php

declare(strict_types=1);

namespace App\User\Application\Query\FindUserByEmail;

use App\Shared\Infrastructure\Bus\Query\QueryInterface;

final class FindUserByEmailQuery implements QueryInterface
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
