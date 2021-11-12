<?php

namespace App\Service\Api\DataTransformer\Auth;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\ApiResource\Auth\Authentication;
use App\Dto\Auth\UserLogoutDto;
use App\Dto\Auth\UserPasswordResetDto;
use App\Dto\Auth\UserRefreshDto;

final class UserAuthDataTransformer implements DataTransformerInterface
{
    public function __construct()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function transform($object, string $to, array $context = []): object
    {
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // If it's a UserLogoutDto, UserPasswordResetDto or UserRefreshDto we transformed the data already
        if ($data instanceof UserLogoutDto || $data instanceof UserPasswordResetDto || $data instanceof UserRefreshDto) {
            return false;
        }

        return Authentication::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
