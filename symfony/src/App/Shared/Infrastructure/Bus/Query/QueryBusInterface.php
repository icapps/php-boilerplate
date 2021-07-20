<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query);
}
