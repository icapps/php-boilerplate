<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\General\StatusDto;
use App\Dto\User\UserPasswordDto;
use App\Entity\User;
use App\Exception\InvalidPasswordException;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\Api\General\ApiService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserPasswordDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataProvider\Examples
 */
final class UserPasswordDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private ApiService $apiService,
        private UserPasswordEncoderInterface $passwordEncoder,
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
        private TranslatorInterface $translator,
        private Security $security
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserPasswordDto;
    }

    /**
     * {@inheritDoc}
     * @throws InvalidPasswordException
     */
    public function persist($data)
    {
        // Check user.
        /** @var User $user */
        if (!$user = $this->security->getUser()) {
            throw new AuthenticationException();
        }

        // Check password.
        /** @var UserPasswordDto $data */
        if (!$this->passwordEncoder->isPasswordValid($user, $data->oldPassword)) {
            $error = $this->translator->trans('icapps.registration.password.invalid', [], 'validators');
            throw new InvalidPasswordException($error, 400);
        }

        // Update password.
        try {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $data->password));
            $this->userRepository->save($user);
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
            // Silence.
        }

        // Return status output.
        return new StatusDto(
            Response::HTTP_OK,
            $this->translator->trans('icapps.registration.password.updated', [], 'validators')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data)
    {
        // this method just need to be presented
    }
}
