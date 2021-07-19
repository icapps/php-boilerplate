<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

interface CommandBusInterface
{
    public function handle(CommandInterface $command);
}
