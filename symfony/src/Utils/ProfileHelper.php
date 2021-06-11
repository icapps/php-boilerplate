<?php

namespace App\Utils;

use App\Component\Model\ProfileInterface;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\Model\ProfileRepositoryInterface;
use App\Repository\ProfileRepository;

class ProfileHelper
{
    public function __construct(
        private ProfileRepository $profileRepository,
    ) {
    }

    /**
     * Gives the default User Profile
     * @return string
     */
    public function getDefaultProfileType(): string
    {
        return Profile::PROFILE_TYPE;
    }

    /**
     * Gives the correct User Profile
     *
     * @param User $user
     * @return ProfileInterface
     */
    public function getProfile(User $user): ProfileInterface
    {
        // Extend Profile types if more Profiles are used
        return match ($user->getProfileType()) {
            Profile::PROFILE_TYPE => $this->profileRepository->findById($user->getProfileId()),
            default => $this->profileRepository->findById($user->getProfileId())
        };
    }

    /**
     * Gives the correct User ProfileRepository
     *
     * @param User $user
     * @return ProfileRepositoryInterface
     */
    public function getProfileRepository(User $user): ProfileRepositoryInterface
    {
        // Extend Profile types if more Profiles are used
        return match ($user->getProfileType()) {
            Profile::PROFILE_TYPE => $this->profileRepository,
            default => $this->profileRepository
        };
    }
}
