<?php

namespace App\Service\Website\User;

use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\UserRepository;
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
        private MailHelper $mailHelper,
        private LoggerInterface $logger,
        private UserPasswordEncoderInterface $encoder
    ) {
    }

    /**
     * Activate user.
     *
     * @param string $activationToken
     *
     * @return User|null
     *
     * @throws LoaderError
     * @throws ORMException
     * @throws OptimisticLockException
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

        // No profile.
        if (!$profile = $user->getProfile()) {
            $this->logger->error(sprintf('User could not be activated [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);
            return null;
        }

        // Send confirmation mail.
        $this->mailHelper->sendRegistrationConfirmationMail($user, $profile);

        // Log it.
        $this->logger->info(sprintf('User activated [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $user;
    }

    /**
     * Activete user pending email.
     *
     * @param string $activationToken
     *
     * @return User|null
     *
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

        // Missing pending mail.
        if (!$pendingEmail = $user->getPendingEmail()) {
            return null;
        }

        $user->setEmail($pendingEmail);
        $user->setPendingEmail(null);
        $user->setActivationToken(null);
        $this->userRepository->save($user);

        $this->logger->info(sprintf('User pending email change confirmed [id: "%s", e-mail: "%s"]', $user->getId(), $user->getEmail()), ['user' => $user]);

        return $user;
    }

    /**
     * Validate given reset token.
     *
     * @param string $resetToken
     *
     * @return User|null
     */
    public function validatePasswordResetToken(string $resetToken): ?User
    {
        return $this->userRepository->findByResetToken($resetToken);
    }

    /**
     * User password reset.
     *
     * @param User $user
     * @param string $password
     *
     * @return User
     *
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
