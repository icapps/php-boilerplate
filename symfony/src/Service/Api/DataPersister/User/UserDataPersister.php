<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\ApiResource\User\User;
use App\Dto\User\UserProfileDto;
use App\Mail\MailHelper;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\AuthUtils;
use App\Utils\UuidEncoder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataProvider\Examples
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
        private LoggerInterface $logger
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
        /** @var User $data */
        if (!$user = $this->userRepository->findByEncodedUuid($data->userSid)) {
            throw new NotFoundHttpException('User not found', null, 404);
        }

        // Check access.
        if ($this->security->getUser() !== $user) {
            throw new AuthenticationException();
        }

        // Update user.
        /** @var UserProfileDto $data */
        $user->setLanguage($data->language);

        // Update email: pending until activation.
        if ($data->email !== $user->getEmail()) {
            $user->setPendingEmail($data->email);
            $user->setActivationToken(AuthUtils::getUniqueToken());
        }

        $this->userRepository->save($user);

        // Update profile.
        $profile = $user->getProfile();
        $profile->setFirstName($data->firstName);
        $profile->setLastName($data->lastName);
        $this->profileRepository->save($profile);

        // Send confirmation mail.
        if ($user->getPendingEmail()) {
            try {
                $this->mailHelper->sendPendingEmailActivation($user, $profile);
            } catch (\Exception $e) {
                // Silent failure.
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
    public function remove($data)
    {
        // this method just need to be presented
    }
}
