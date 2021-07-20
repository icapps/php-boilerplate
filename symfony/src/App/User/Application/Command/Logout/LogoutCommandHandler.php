<?php

declare(strict_types=1);

namespace App\User\Application\Command\Logout;

use App\Shared\Infrastructure\Bus\Command\CommandHandlerInterface;
use App\User\Infrastructure\Repository\DeviceRepository;

final class LogoutCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private DeviceRepository $deviceRepository
    ) {
        //
    }

    public function __invoke(LogoutCommand $command): void
    {
        $device = $this->deviceRepository->findOneBy([
            'user' => $command->user,
            'deviceId' => $command->deviceId,
        ]);

        if ($device) {
            $this->deviceRepository->remove($device);
        }
    }
}
