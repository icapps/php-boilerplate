<?php

namespace App\Service\Api\DataTransformer\Auth;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ApiResource\Auth\Register;
use App\Dto\Auth\UserRegisterDto;

final class UserRegisterDataTransformer implements DataTransformerInterface
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
        // If it's a UserRegisterDto we transformed the data already
        if ($data instanceof UserRegisterDto) {
            return false;
        }

        return Register::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
