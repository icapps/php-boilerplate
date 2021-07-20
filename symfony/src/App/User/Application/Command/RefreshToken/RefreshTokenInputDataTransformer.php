<?php

declare(strict_types=1);

namespace App\User\Application\Command\RefreshToken;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\User\Application\Command\Dto\RefreshTokenInput;

final class RefreshTokenInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        if (!$object instanceof RefreshTokenInput) {
            throw new \InvalidArgumentException(\sprintf('Object is not an instance of %s', RefreshTokenInput::class));
        }

        // Validation.
        $this->validator->validate($object, $context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return RefreshTokenInput::class === ($context['input']['class'] ?? null);
    }
}
