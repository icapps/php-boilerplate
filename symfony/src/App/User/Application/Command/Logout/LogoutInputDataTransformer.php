<?php

declare(strict_types=1);

namespace App\User\Application\Command\Logout;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\User\Application\Command\Dto\LogoutInput;

final class LogoutInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        if (!$object instanceof LogoutInput) {
            throw new \InvalidArgumentException(\sprintf('Object is not an instance of %s', LogoutInput::class));
        }

        // Validation.
        $this->validator->validate($object, $context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return LogoutInput::class === ($context['input']['class'] ?? null);
    }
}
