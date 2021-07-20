<?php

declare(strict_types=1);

namespace App\User\Application\Command\RefreshToken;

use App\Shared\Infrastructure\Bus\Command\CommandInterface;
use Symfony\Component\HttpFoundation\Request;

final class RefreshTokenCommand implements CommandInterface
{
    public Request $currentRequest;

    public function __construct(
        Request $currentRequest
    ) {
        $this->currentRequest = $currentRequest;
    }
}
