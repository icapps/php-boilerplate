<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\Auth\UserLogoutDto;
use App\Dto\General\StatusDto;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LogoutDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
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
        private Security $security,
        private TranslatorInterface $translator
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserLogoutDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Default response.
        $output = new StatusDto(
            Response::HTTP_OK,
            $this->translator->trans('icapps.logout.success.title', [], 'messages'),
            $this->translator->trans('icapps.logout.success.message', [], 'messages')
        );

        // Mobile should throw away user session, so we only have to clear device.
        /** @var UserLogoutDto $data */
        try {
            if (
                $device = $this->deviceRepository->findOneBy([
                'user' => $this->security->getUser(),
                'deviceId' => $data->deviceSid
                ])
            ) {
                $this->deviceRepository->remove($device->getId());
            }
        } catch (\Exception $e) {
            $output->status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $output->title = $this->translator->trans('icapps.logout.error.title', [], 'messages');
            $output->detail = $e->getMessage();
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
