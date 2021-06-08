<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\User\UserDeviceDto;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDeviceDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\User
 */
final class UserDeviceDataPersister implements DataPersisterInterface
{
    /**
     * UserDeviceDataPersister constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ValidatorInterface $validator
     * @param DeviceRepository $deviceRepository
     * @param Security $security
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordEncoderInterface $userPasswordEncoder,
        private ValidatorInterface $validator,
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
        if (!$device = $this->deviceRepository->findOneBy([
            'user' => $this->security->getUser(),
            'deviceId' => $data->deviceId
        ])) {
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
    public function remove($data)
    {
        // this method just need to be presented
    }
}
