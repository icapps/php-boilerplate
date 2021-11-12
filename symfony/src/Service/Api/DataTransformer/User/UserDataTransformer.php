<?php

namespace App\Service\Api\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\ApiResource\User\User;
use App\Dto\User\UserProfileDto;

final class UserDataTransformer implements DataTransformerInterface
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
        // @TODO:: optimize? $context["input"]["class"] === UserPasswordDto, differentiate from others?
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // If it's a UserProfileDto we transformed the data already
        if ($data instanceof UserProfileDto) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
