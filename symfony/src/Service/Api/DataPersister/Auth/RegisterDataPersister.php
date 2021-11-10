<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\User\UserProfileDto;
use App\Dto\Auth\UserRegisterDto;
use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\AuthUtils;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class RegisterDataPersister implements DataPersisterInterface
{
    public function __construct(
        private UserPasswordEncoderInterface $userPasswordEncoder,
        private ValidatorInterface $validator,
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
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
        return $data instanceof UserRegisterDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Create user.
        /** @var UserRegisterDto $data */
        $user = $this->userRepository->create();
        $user->setRoles([User::ROLE_USER]);
        $user->setEmail($data->email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $data->password));
        $user->setLanguage($data->language);

        // User only enabled by confirmation mail.
        $user->disable();
        $user->setActivationToken(AuthUtils::getUniqueToken());

        // Create user profile.
        $profile = $this->profileRepository->create();
        $profile->setFirstName($data->firstName);
        $profile->setLastName($data->lastName);

        // Validate and save profile.
        $context['groups'] = 'orm-registration';
        $this->validator->validate($profile, $context);

        // Validate and save user.
        $this->profileRepository->beginTransaction();
        $this->userRepository->beginTransaction();
        try {
            // Save + set user profile.
            $this->profileRepository->save($profile);
            $user->setProfile($profile);

            // Validate user.
            $context['groups'] = 'orm-registration';
            $this->validator->validate($user, $context);

            // Save user.
            $this->userRepository->save($user);

            // Commit changes.
            $this->profileRepository->commit();
            $this->userRepository->commit();
        } catch (ValidationException $exception) {
            $this->profileRepository->rollback();
            $this->userRepository->rollback();
            throw $exception;
        }

        // Send activation mail.
        try {
            if ($userProfile = $user->getProfile()) {
                $this->mailHelper->sendRegistrationActivationMail($user, $userProfile);
            }
        } catch (\Exception $e) {
            // Silent failure.
            $this->logger->critical('User activation mail failure: ' . $e->getMessage());
        }

        // Create output.
        $output = new UserProfileDto();
        $output->firstName = $data->firstName;
        $output->lastName = $data->lastName;
        $output->email = $data->email;
        $output->language = $data->language;

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
