<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\Auth\UserRefreshDto;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RefreshDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 *
 * @package App\Service\Api\DataPersister\Auth
 */
final class RefreshDataPersister implements DataPersisterInterface
{
    /**
     * RefreshDataPersister constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ValidatorInterface $validator
     * @param RefreshToken $refreshTokenService
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordEncoderInterface $userPasswordEncoder,
        private ValidatorInterface $validator,
        private RefreshToken $refreshTokenService,
        private RequestStack $requestStack
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof UserRefreshDto;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        return $this->refreshTokenService->refresh($this->requestStack->getCurrentRequest());
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data)
    {
        // this method just need to be presented
    }
}
