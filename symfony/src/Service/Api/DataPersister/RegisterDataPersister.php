<?php

namespace App\Service\Api\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\ApiResource\Register;
use App\Dto\RegisterOutput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterDataPersister
 *
 * This is a custom DataPersister for which incoming data can be handled, persisted and customized in any way.
 * More information: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister
 */
final class RegisterDataPersister implements DataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordEncoderInterface $userPasswordEncoder
    ) {
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

        $user = new User();
        $user->setRoles([User::ROLE_USER]);
        $user->setEmail($data->getEmail());
        $user->setUsername($data->getFirstName().'-'.$data->getLastName());
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $data->getPassword()));
        $user->setLanguage($data->getLanguage());
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

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
