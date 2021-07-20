<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

interface CommandHandlerInterface extends MessageHandlerInterface
{
}
