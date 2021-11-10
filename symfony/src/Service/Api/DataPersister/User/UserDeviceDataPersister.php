<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\User\UserDeviceDto;
use App\Repository\DeviceRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDeviceDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class UserDeviceDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private DeviceRepository $deviceRepository,
        private Security $security
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserDeviceDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data): object
    {
        /** @var UserDeviceDto $data */
        if (
            !$device = $this->deviceRepository->findOneBy([
            'user' => $this->security->getUser(),
            'deviceId' => $data->deviceSid
            ])
        ) {
            throw new NotFoundHttpException('Device not found', null, 404);
        }

        // Update device.
        $device->setDeviceToken($data->deviceToken);
        $this->deviceRepository->save($device);

        return new UserDeviceDto(
            $device->getDeviceId(),
            $device->getDeviceToken(),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data): void
    {
        // this method just need to be presented
    }
}
