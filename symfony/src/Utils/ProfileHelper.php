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

    /**
     * Update profile fields.
     *
     * @param Request $request
     * @param ProfileInterface $profile
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function updateProfileFields(Request $request, ProfileInterface $profile, User $user)
    {
        $data = $request->request->all();
        $response_language = $request->headers->get('app-language-code') ?? 'nl';

        // Get current email.
        $currentEmail = $user->getEmail();

        if ($errors = $this->authService->handleValidation($profile, ['orm-profile-update'], $response_language)) {
            return $errors;
        }

        // User validation.
        if ($errors = $this->authService->handleValidation($user, ['orm-user-update'], $response_language)) {
            return $errors;
        }

        // Update email: set pending until activation.
        if ($currentEmail != $user->getEmail()) {
            $user->setEmail($currentEmail);
            $user->setPendingEmail($data['email']);
            $user->setActivationToken($this->authService->getUniqueToken());
        }

        // Save profile.
        match ($user->getProfileType()) {
            Profile::PROFILE_TYPE => $this->profileRepository->save($profile),
        };

        // Save user.
        $this->userRepository->save($user);

        if ($user->getPendingEmail()) {
            // Send mail.
            $this->mailHelper->sendPendingEmailActivation($user, $profile);
        }

        // Log it.
        $this->logger->info(sprintf('User "%s" has updated it\'s profile "%s".', $user->getId(), $profile->getId()));

        $success = $this->translator->trans('icapps.profile.update.completed', [], 'validators');
        return $this->authService->respondWithSuccess($success);
    }

    /**
     * Update password.
     *
     * @param Request $request
     * @param ProfileInterface $profile
     * @param $user
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePassword(Request $request, ProfileInterface $profile, $user)
    {
        // Required fields.
        $oldPassword = $request->get('oldPassword');
        $newPassword = $request->get('newPassword');

        // Check required params.
        if (!$oldPassword || !$newPassword) {
            $error = $this->translator->trans('icapps.registration.password.required_fields', [], 'validators');
            return $this->authService->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors($error);
        }

        // Check current password.
        if (!$this->passwordEncoder->isPasswordValid($user, $oldPassword)) {
            $error = $this->translator->trans('icapps.registration.password.invalid', [], 'validators');
            return $this->authService->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithErrors($error);
        }

        // Set new password + validation.
        $user->setPassword($newPassword);
        if ($errors = $this->authService->handleValidation($user, ['orm-update-password'])) {
            return $errors;
        }

        // Save user.
        $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));
        $this->userRepository->save($user);

        // Log it.
        $this->logger->info(sprintf('User "%s" has updated it\'s password "%s".', $user->getId(), $profile->getId()));

        $success = $this->translator->trans('icapps.password.update.completed', [], 'validators');
        return $this->authService->respondWithSuccess($success);
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
