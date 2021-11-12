<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\General\StatusDto;
use App\Dto\User\UserPasswordDto;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\UserRepository;
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
 */
final class UserPasswordDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private UserPasswordEncoderInterface $passwordEncoder,
        private UserRepository $userRepository,
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
     */
    public function persist($data)
    {
        // Check user.
        if (!$user = $this->security->getUser()) {
            throw new AuthenticationException();
        }

        // Check password.
        /** @var UserPasswordDto $data */
        if (!$this->passwordEncoder->isPasswordValid($user, $data->oldPassword)) {
            $error = $this->translator->trans('icapps.registration.password.invalid', [], 'validators');
            throw new ApiException(Response::HTTP_UNPROCESSABLE_ENTITY, $error);
        }

        // Update password.
        /** @var User $user */
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
            $this->translator->trans('icapps.registration.password.updated.title', [], 'validators'),
            $this->translator->trans('icapps.registration.password.updated.message', [], 'validators')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data): void
    {
        // this method just need to be presented
    }
}
