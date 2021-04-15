<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ApiResource\Authentication\Logout;
use App\Dto\StatusDto;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class LogoutDataPersister
 *
 * This is a custom DataPersister for which incoming data can be handled, persisted and customized in any way.
 * More information: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class LogoutDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
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
        return $data instanceof Logout;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Create response.
        $output = new StatusDto();
        $output->code = Response::HTTP_CREATED;
        $output->message = 'Logout successful';

        // Mobile should throw away user session so we only have to clear device.
        try {
            if ($device = $this->deviceRepository->findOneBy(['user' => $this->security->getUser(), 'deviceId' => $data->getDeviceId()])) {
                $this->deviceRepository->remove($device->getId());
            }
        } catch (\Exception $e) {
            $output->code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $output->message = $e->getMessage();
        }

        return $output;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data)
    {
        // this method just need to be presented
    }
}
