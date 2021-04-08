<?php

namespace App\Service\Website;

use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\CompanyHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{

    public function __construct(private UserRepository $userRepository, private ProfileRepository $profileRepository, private ValidatorInterface $validator, private TranslatorInterface $translator, private MailHelper $mailHelper, private LoggerInterface $logger, private UserPasswordEncoderInterface $encoder, private CompanyHelper $companyHelper)
    {
    }

    public function activateUser(string $activationToken): ?User
    {
        // Get user by token.
        $user = $this->userRepository->findByActivationToken($activationToken);

        // Incorrect token.
        if (!$user) {
            return null;
        }

        // Enable user.
        $user->setActivationToken(null);
        $user->enable();
        $this->userRepository->save($user);

        $profile = $this->profileRepository->findById($user->getProfileId());

        // Send confirmation mail.
        $this->mailHelper->sendRegistrationConfirmationMail($user, $profile, $this->companyHelper);

        $this->logger->info(sprintf('User activated [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $user;
    }

    public function activatePendingEmailOfUser(string $activationToken): ?User
    {
        // Get user by token.
        $user = $this->userRepository->findByActivationToken($activationToken);

        // Incorrect token.
        if (!$user) {
            return null;
        }

        $user->setEmail($user->getPendingEmail());
        $user->setPendingEmail(null);
        $user->setActivationToken(null);
        $this->userRepository->save($user);

        $this->logger->info(sprintf('User pending email change confirmed [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $user;
    }

    public function validatePasswordResetToken(string $resetToken): ?User
    {
        return $this->userRepository->findByResetToken($resetToken);
    }

    public function passwordResetUser(User $user, string $password): User
    {
        // Update password.
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setResetToken(null);
        $user->enable();
        $this->userRepository->save($user);

        $this->logger->info(sprintf('User password reset completed [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $user;
    }
}
