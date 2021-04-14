<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Device;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DeviceDataPersister
 *
 * This is a custom DataPersister for which incoming data can be handled, persisted and customized in any way.
 * More information: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class DeviceDataPersister implements DataPersisterInterface
{
    /**
     * DeviceDataPersister constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ValidatorInterface $validator
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordEncoderInterface $userPasswordEncoder,
        private ValidatorInterface $validator,
        private DeviceRepository $deviceRepository
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof Device;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data): object
    {
        /** @var Device $data */
        // Validate device input.
        $context["groups"] = "api-write";
        $this->validator->validate($data, $context);

        // Persist.
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data)
    {
        // this method just need to be presented
    }
}
