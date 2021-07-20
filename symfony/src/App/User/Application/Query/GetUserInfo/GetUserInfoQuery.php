<?php

declare(strict_types=1);

namespace App\User\Application\Query\GetUserInfo;

use App\Shared\Infrastructure\Bus\Query\QueryInterface;

final class GetUserInfoQuery implements QueryInterface
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
