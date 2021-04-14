<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ApiResource\Authentication\Register;
use App\Dto\RegisterOutput;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Utils\AuthUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterDataPersister
 *
 * This is a custom DataPersister for which incoming data can be handled, persisted and customized in any way.
 * More information: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class RegisterDataPersister implements DataPersisterInterface
{
    public function __construct(
        private UserPasswordEncoderInterface $userPasswordEncoder,
        private ValidatorInterface $validator,
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof Register;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        // Create response.
        $output = new RegisterOutput();

        /** @var Register $data */
        $output->firstName = $data->getFirstName();
        $output->lastName = $data->getLastName();
        $output->email = $data->getEmail();
        $output->language = $data->getLanguage();

        // Create user.
        $user = $this->userRepository->create();
        $user->setRoles([User::ROLE_USER]);
        $user->setEmail($data->getEmail());
        $user->setUsername($data->getFirstName().'-'.$data->getLastName());
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $data->getPassword()));
        $user->setLanguage($data->getLanguage());
        $user->setProfileType(Profile::PROFILE_TYPE);

        // Create user profile.
        $profile = $this->profileRepository->create();
        $profile->setFirstName($data->getFirstName());
        $profile->setLastName($data->getLastName());

        // Validate and save.
        $context["groups"] = "register:api-write";
        $this->validator->validate($profile, $context);
        $this->profileRepository->save($profile);

        $user->setProfileId($profile->getId());

        // User only enabled by confirmation mail: set activation token.
        $user->disable();
        $user->setActivationToken(AuthUtils::getUniqueToken());

        // Validate and save.
        $context["groups"] = "register:api-write";
        $this->validator->validate($user, $context);

        $this->userRepository->save($user);

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
