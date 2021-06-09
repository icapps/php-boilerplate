<?php

namespace App\Service\Api\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ApiResource\User\UserDevice;
use App\Dto\User\UserDeviceDto;

final class UserDeviceDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
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
        // If it's a UserDeviceDto we transformed the data already
        if ($data instanceof UserDeviceDto) {
            return false;
        }

        return UserDevice::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
