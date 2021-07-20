<?php

declare(strict_types=1);

namespace App\User\Application\Command\RefreshToken;

use App\Shared\Infrastructure\Bus\Command\CommandHandlerInterface;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;

final class RefreshTokenCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RefreshToken $refreshTokenService
    ) {
        //
    }

    public function __invoke(RefreshTokenCommand $command)
    {
        return $this->refreshTokenService->refresh($command->currentRequest);
    }
}
