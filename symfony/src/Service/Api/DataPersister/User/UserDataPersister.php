<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Dto\User\UserProfileDto;
use App\Exception\UserNotFoundException;
use App\Mail\MailHelper;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\AuthUtils;
use App\Utils\UuidEncoder;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class UserDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
        private Security $security,
        private MailHelper $mailHelper,
        private LoggerInterface $logger,
        private ValidatorInterface $validator
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserProfileDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Load user.
        /** @var UserProfileDto $data */
        if (!$user = $this->userRepository->findByEncodedUuid($data->userSid)) {
            throw new UserNotFoundException('User not found');
        }

        // Check access.
        if ($this->security->getUser() !== $user) {
            throw new AuthenticationException();
        }

        // Update user.
        /** @var User $user */
        $user->setLanguage($data->language);

        // Update email: pending until activation.
        if ($data->email !== $user->getEmail()) {
            // Set pending email.
            $user->setPendingEmail($data->email);

            // Keep track of current email.
            $currentEmail = $user->getEmail();

            // Validate new email.
            $user->setEmail($data->email);
            $context['groups'] = 'orm-user-update';
            $this->validator->validate($user, $context);

            // Reset current email.
            $user->setEmail($currentEmail);

            // Set activation token.
            $user->setActivationToken(AuthUtils::getUniqueToken());
        }

        // Save user.
        $this->userRepository->save($user);

        // Get profile.
        $profile = $user->getProfile();

        if (!$profile) {
            throw new UserNotFoundException('User profile not found');
        }

        // Update profile.
        $profile->setFirstName($data->firstName);
        $profile->setLastName($data->lastName);
        $this->profileRepository->save($profile);

        // Send confirmation mail.
        if ($user->getPendingEmail()) {
            try {
                $this->mailHelper->sendPendingEmailActivation($user, $profile);
            } catch (\Exception $e) {
                $this->logger->critical('User confirmation mail failure: ' . $e->getMessage());
            }
        }

        // Create output.
        $output = new UserProfileDto();
        $output->userSid = UuidEncoder::encode($user->getUuid());
        $output->firstName = $profile->getFirstName();
        $output->lastName = $profile->getLastName();
        $output->email = $user->getEmail();
        $output->language = $user->getLanguage();

        return $output;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data): void
    {
        // this method just need to be presented
    }
}
