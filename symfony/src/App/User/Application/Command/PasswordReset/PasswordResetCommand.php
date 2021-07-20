<?php

declare(strict_types=1);

namespace App\User\Application\Command\PasswordReset;

use App\Shared\Infrastructure\Bus\Command\CommandInterface;
use App\User\Domain\ValueObject\Email;

final class PasswordResetCommand implements CommandInterface
{
    public Email $email;

    public function __construct(
        Email $email
    ) {
        $this->email = $email;
    }
}
