<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\ApiResource\User\User;
use App\Dto\User\UserProfileDto;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\ProfileHelper;
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
        private Security $security,
        private ProfileHelper $profileHelper,
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
        if (!$user = $this->userRepository->find($data->id)) {
            throw new NotFoundHttpException('User not found', null, 404);
        }

        // Check access.
        if ($this->security->getUser() !== $user) {
            throw new AuthenticationException();
        }

        // Get user profile.
        $profile = $this->profileHelper->getProfile($user);

        /** @var UserProfileDto $data */
        // Update user.
        // TODO: check email
        // Update email: set pending until activation.
//        if ($currentEmail != $user->getEmail()) {
//            $user->setEmail($currentEmail);
//            $user->setPendingEmail($data['email']);
//            $user->setActivationToken($this->authService->getUniqueToken());
//        }
//        if ($user->getPendingEmail()) {
//            // Send mail.
//            $this->mailHelper->sendPendingEmailActivation($user, $profile);
//        }
        $user->setLanguage($data->language);
        $this->userRepository->save($user);

        // Update profile.
        $profile->setFirstName($data->firstName);
        $profile->setLastName($data->lastName);
        $this->profileHelper->getProfileRepository($user)->save($profile);

        // Create output.
        $output = new UserProfileDto();
        $output->id = $user->getId();
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
