<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\ApiResource\User\User;
use App\Dto\User\UserProfileDto;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDataPersister
 *
 * This is a custom DataProvider for which getItem and getCollection can be customized to retrieve data.
 * More information: https://api-platform.com/docs/core/data-providers.
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
        private Security $security
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
        $user = $this->userRepository->find($data->id);

        // @TODO:: unify security checks?
        // Check user + access.
        if (!$user || $this->security->getUser() !== $user) {
            return null;
        }

        // Get user profile.
        $profile = $user->getProfile();

        // Update profile.
        /** @var UserProfileDto $data */
        $profile->setFirstName($data->firstName);
        $profile->setLastName($data->lastName);
        $this->profileRepository->save($profile);

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
