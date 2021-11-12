<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\Auth\UserRefreshDto;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class RefreshDataPersister
 *
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class RefreshDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
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
        if (!$currentRequest = $this->requestStack->getCurrentRequest()) {
            throw new AuthenticationException('Refresh token failed.');
        }

        return $this->refreshTokenService->refresh($currentRequest);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data): void
    {
        // this method just need to be presented
    }
}
