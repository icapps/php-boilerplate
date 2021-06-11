<?php

namespace App\Service\Api\DataPersister\Auth;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Dto\Auth\UserRefreshDto;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @param RefreshToken $refreshTokenService
     * @param RequestStack $requestStack
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
