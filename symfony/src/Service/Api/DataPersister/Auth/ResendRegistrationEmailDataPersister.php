<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\Auth\UserResendRegistrationEmailDto;
use App\Dto\General\StatusDto;
use App\Mail\MailHelper;
use App\Repository\UserRepository;
use App\Utils\ProfileHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ResendRegistrationEmailDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class ResendRegistrationEmailDataPersister implements DataPersisterInterface
{
    /**
     * ResendRegistrationEmailDataPersister constructor.
     * @param UserRepository $userRepository
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     * @param ProfileHelper $profileHelper
     * @param MailHelper $mailHelper
     */
    public function __construct(
        private UserRepository $userRepository,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private ProfileHelper $profileHelper,
        private MailHelper $mailHelper,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserResendRegistrationEmailDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        /** @var UserResendRegistrationEmailDto $data */
        // Default response.
        $output = new StatusDto(
            Response::HTTP_OK,
            $this->translator->trans('icapps.mail.registration.resend', [], 'messages'),
        );

        // Get User
        // Change profile type here if needed
        $user = $this->userRepository->findOneBy(['email' => $data->email, 'profileType' => $this->profileHelper->getDefaultProfileType(), 'enabled' => 0]);
        if (!$user) {
            //silent error
            $error = $this->translator->trans('icapps.reset.email.not_found', [], 'validators');
            $this->logger->warning($error);
            return $output;
        }

        // Get profile.
        $profile = $this->profileHelper->getProfileRepository($user)->findById($user->getProfileId());

        if (!$profile) {
            //silent error
            $errors = $this->translator->trans('icapps.general.no_profile_found', [], 'messages');
            $this->logger->warning($errors);
            return $output;
        }

        $this->mailHelper->sendRegistrationActivationMail($user, $profile);
        // Log it.
        $this->logger->info(sprintf('Resend registration email requested [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

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
