<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\Auth\UserEmailAvailableDto;
use App\Dto\General\StatusDto;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EmailAvailableDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class EmailAvailableDataPersister implements DataPersisterInterface
{
    /**
     * EmailAvailableDataPersister constructor.
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     */
    public function __construct(
        private UserRepository $userRepository,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserEmailAvailableDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        /** @var UserEmailAvailableDto $data */
        // Default response.
        $output = new StatusDto(
            Response::HTTP_OK,
            $this->translator->trans('icapps.registration.email.available', [], 'validators'),
        );

        try {
            $user = $this->userRepository->create();
            $user->setEmail($data->email);
            // Validate user.
            $context['groups'] = 'orm-email-validation';
            $this->validator->validate($user, $context);
        } catch (ValidationException $exception) {
            throw $exception;
        }

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
