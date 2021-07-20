<?php

declare(strict_types=1);

namespace App\User\Application\Command\SignIn;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Shared\Infrastructure\Bus\Command\CommandHandlerInterface;
use App\User\Infrastructure\Repository\DeviceRepository;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

final class SignInCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private DeviceRepository $deviceRepository,
        private ValidatorInterface $validator
    ) {
        //
    }

    public function __invoke(SignInCommand $command): void
    {
        $user = $command->user;
        $requestPayload = $command->requestPayload;
        $deviceId = $requestPayload['deviceId'] ?? null;
        $deviceToken = $requestPayload['deviceToken'] ?? null;

        // Get user device.
        $device = $this->deviceRepository->findOneBy([
            'user' => $command->user,
            'deviceId' => $deviceId
        ]);

        if (!$device) {
            $device = $this->deviceRepository->create();
        }

        // Update device.
        $device->setUser($user);
        $device->setDeviceId($deviceId);
        $device->setDeviceToken($deviceToken);

        try {
            $this->deviceRepository->store($device);
        } catch (OptimisticLockException | ORMException $e) {
            // Silence.
        }
    }
}
