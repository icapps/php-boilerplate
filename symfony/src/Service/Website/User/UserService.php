<?php

namespace App\Service\Website\User;

use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\ProfileHelper;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserService
{

    public function __construct(
        private UserRepository $userRepository,
        private ProfileHelper $profileHelper,
        private MailHelper $mailHelper,
        private LoggerInterface $logger,
        private UserPasswordEncoderInterface $encoder
    ) {
    }

    /**
     * @param string $activationToken
     * @return User|null
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
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

        $profile = $this->profileHelper->getProfile($user);

        // Send confirmation mail.
        $this->mailHelper->sendRegistrationConfirmationMail($user, $profile);

        $this->logger->info(sprintf('User activated [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $user;
    }

    /**
     * @param string $activationToken
     * @return User|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
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

    /**
     * @param string $resetToken
     * @return User|null
     */
    public function validatePasswordResetToken(string $resetToken): ?User
    {
        return $this->userRepository->findByResetToken($resetToken);
    }

    /**
     * @param User $user
     * @param string $password
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
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
