<?php

declare(strict_types=1);

namespace App\User\Application\Query\FindUserById;

use App\Shared\Infrastructure\Bus\Query\QueryInterface;

final class FindUserByIdQuery implements QueryInterface
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
