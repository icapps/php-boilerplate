<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\User\UserProfileDto;
use App\Dto\Auth\UserRegisterDto;
use App\Entity\User;
use App\Mail\MailHelper;
use App\Repository\UserRepository;
use App\Utils\AuthUtils;
use App\Utils\ProfileHelper;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class RegisterDataPersister implements DataPersisterInterface
{
    /**
     * RegisterDataPersister constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @param ProfileHelper $profileHelper
     * @param MailHelper $mailHelper
     */
    public function __construct(
        private UserPasswordEncoderInterface $userPasswordEncoder,
        private ValidatorInterface $validator,
        private UserRepository $userRepository,
        private ProfileHelper $profileHelper,
        private MailHelper $mailHelper,
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
        /** @var User $user */
        $user = $this->userRepository->create();
        $user->setRoles([User::ROLE_USER]);
        $user->setEmail($data->email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $data->password));
        $user->setLanguage($data->language);
        // Change profile type here if needed
        $user->setProfileType($this->profileHelper->getDefaultProfileType());
        $user->disable();
        $user->setActivationToken(AuthUtils::getUniqueToken());

        // Create user profile.
        $profileRepository = $this->profileHelper->getProfileRepository($user);
        $profile = $profileRepository->create();
        $profile->setFirstName($data->firstName);
        $profile->setLastName($data->lastName);

        // Validate and save profile.
        $context['groups'] = 'orm-registration';
        $this->validator->validate($profile, $context);

        // Validate and save user.
        $profileRepository->beginTransaction();
        $this->userRepository->beginTransaction();
        try {
            // Save + set user profile.
            $profileRepository->save($profile);

            $user->setProfileId($profile->getId());
            // Validate user.
            $context['groups'] = 'orm-registration';
            $this->validator->validate($user, $context);

            // Save user.
            $this->userRepository->save($user);

            // Commit changes.
            $profileRepository->commit();
            $this->userRepository->commit();
        } catch (ValidationException $exception) {
            $profileRepository->rollback();
            $this->userRepository->rollback();
            throw $exception;
        }

        // User only enabled by confirmation mail.
        $this->mailHelper->sendRegistrationActivationMail($user, $profile);

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
    public function remove($data)
    {
        // this method just need to be presented
    }
}
