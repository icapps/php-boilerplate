<?php

namespace App\Service\Api\DataProvider\User;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\ApiResource\User\UserDevice;
use App\Dto\User\UserDeviceDto;
use App\Repository\DeviceRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDeviceDataProvider
 *
 * @link: https://api-platform.com/docs/core/data-providers.
 *
 * @package App\Service\Api\DataProvider\User
 */
final class UserDeviceDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
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
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === UserDevice::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): UserDeviceDto
    {
        /** @var UserDeviceDto $data */
        if (
            !$device = $this->deviceRepository->findOneBy([
            'user' => $this->security->getUser(),
            'deviceId' => $id
            ])
        ) {
            throw new NotFoundHttpException('Device not found', null, 404);
        }

        return new UserDeviceDto(
            $device->getDeviceId(),
            $device->getDeviceToken(),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        return $this->deviceRepository->findAll();
    }
}
