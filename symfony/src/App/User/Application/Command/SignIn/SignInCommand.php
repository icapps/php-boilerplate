<?php

declare(strict_types=1);

namespace App\User\Application\Command\SignIn;

use App\Shared\Infrastructure\Bus\Command\CommandInterface;
use App\User\Infrastructure\Doctrine\User;

final class SignInCommand implements CommandInterface
{
    public User $user;

    public array $requestPayload;

    public function __construct(
        User $user,
        array $requestPayload
    ) {
        $this->user = $user;
        $this->requestPayload = $requestPayload;
    }
}
