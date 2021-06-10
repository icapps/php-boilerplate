<?php

namespace App\Utils;

use App\Component\Model\ProfileInterface;
use App\Entity\Profile;
use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\Model\ProfileRepositoryInterface;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\Api\General\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ProfileHelper
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AuthService $authService,
        private ProfileRepository $profileRepository,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private UserPasswordEncoderInterface $passwordEncoder,
        private LoggerInterface $logger,
        private MailHelper $mailHelper
    ) {
    }
// TODO: continue check with Data persister
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
     * Gives the correct User Profile
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
