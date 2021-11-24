<?php

namespace App\Service\Api\DataPersister\User;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\General\StatusDto;
use App\Dto\User\UserPasswordDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\ConstraintViolationUtils;
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
            $violations = ConstraintViolationUtils::createViolationList(
                $this->translator->trans('icapps.registration.password.invalid', [], 'validators'),
                'oldPassword',
                $data->oldPassword
            );

            throw new ValidationException($violations);
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
