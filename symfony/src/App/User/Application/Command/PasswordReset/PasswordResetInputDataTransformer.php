<?php

declare(strict_types=1);

namespace App\User\Application\Command\PasswordReset;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\User\Application\Command\Dto\PasswordResetInput;

final class PasswordResetInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        if (!$object instanceof PasswordResetInput) {
            throw new \InvalidArgumentException(\sprintf('Object is not an instance of %s', PasswordResetInput::class));
        }

        // Validation.
        $this->validator->validate($object, $context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return PasswordResetInput::class === ($context['input']['class'] ?? null);
    }
}
