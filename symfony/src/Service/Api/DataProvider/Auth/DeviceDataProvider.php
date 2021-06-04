<?php

namespace App\Service\Api\DataProvider\Auth;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Device;
use App\Repository\DeviceRepository;
use Symfony\Component\Security\Core\Security;

/**
 * Class DeviceDataProvider
 *
 * @link: https://api-platform.com/docs/core/data-providers.
 *
 * @package App\Service\Api\DataProvider\Auth
 */
final class DeviceDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
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
        return $resourceClass === Device::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Device
    {
        // Get device by user and deviceId.
        return $this->deviceRepository->findOneBy([
            'user' => $this->security->getUser(),
            'deviceId' => $id
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        return $this->deviceRepository->findAll();
    }
}
