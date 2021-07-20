<?php

declare(strict_types=1);

namespace App\User\Application\Command\Logout;

use App\Shared\Infrastructure\Bus\Command\CommandInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class LogoutCommand implements CommandInterface
{
    public UserInterface $user;

    public string $deviceId;

    public function __construct(
        UserInterface $user,
        string $deviceId
    ) {
        $this->user = $user;
        $this->deviceId = $deviceId;
    }
}
