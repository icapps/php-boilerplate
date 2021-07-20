<?php

declare(strict_types=1);

namespace App\User\Application\Command\SignUp;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\User\Application\Command\Dto\SignUpInput;

final class SignUpInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = [])
    {
        if (!$object instanceof SignUpInput) {
            throw new \InvalidArgumentException(\sprintf('Object is not an instance of %s', SignUpInput::class));
        }

        // Validation.
        $this->validator->validate($object, $context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return SignUpInput::class === ($context['input']['class'] ?? null);
    }
}
