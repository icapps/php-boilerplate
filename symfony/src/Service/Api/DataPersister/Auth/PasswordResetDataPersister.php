<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\Auth\UserPasswordResetDto;
use App\Dto\General\StatusDto;
use App\Mail\MailHelper;
use App\Repository\DeviceRepository;
use App\Repository\UserRepository;
use App\Service\Website\User\UserService;
use App\Utils\AuthUtils;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PasswordResetDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class PasswordResetDataPersister implements DataPersisterInterface
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
        private UserService $userService,
        private UserRepository $userRepository,
        private MailHelper $mailHelper,
        private LoggerInterface $logger,
        private TranslatorInterface $translator
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserPasswordResetDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Default response.
        $output = new StatusDto(
            Response::HTTP_OK,
            $this->translator->trans('icapps.mail.reset_password.sent', [], 'messages'),
        );

        // Find user.
        if (!$user = $this->userRepository->findOneBy(['email' => $data->email])) {
            // Silent error
            $errors = $this->translator->trans('icapps.reset.email.not_found', [], 'validators');
            $this->logger->warning($errors);
            return $output;
        }

        // Set reset token for user.
        try {
            $user->setResetToken(AuthUtils::getUniqueToken());
            $this->userRepository->save($user);
        } catch (\Exception $e) {
            // Silent failure.
            $this->logger->critical('User password reset failure: ' . $e->getMessage());
        }

        // Send reset mail.
        try {
            $this->mailHelper->sendUserPasswordResetMail($user, $user->getProfile());
        } catch (\Exception $e) {
            // Silent failure.
            $this->logger->critical('User password reset failure: ' . $e->getMessage());
        }

        // Log it.
        $this->logger->info(sprintf('User password reset requested [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

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
