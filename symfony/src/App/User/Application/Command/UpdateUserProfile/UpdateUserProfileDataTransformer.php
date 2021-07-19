<?php

declare(strict_types=1);

namespace App\User\Application\Command\UpdateUserProfile;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\User\Application\Command\Dto\UserProfileInput;

final class UpdateUserProfileDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        if (!$object instanceof UserProfileInput) {
            throw new \InvalidArgumentException(\sprintf('Object is not an instance of %s', UserProfileInput::class));
        }

        // Validation.
        $this->validator->validate($object, $context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserProfileInput::class === ($context['input']['class'] ?? null);
    }
}
