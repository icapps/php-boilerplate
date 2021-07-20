<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject\Auth;

use Symfony\Component\PasswordHasher\Hasher\CheckPasswordLengthTrait;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class HashedPassword
{
    use CheckPasswordLengthTrait;

    public const COST = 12;

    private string $hashedPassword;

    public const MIN_PASSWORD_LENGTH = 6;

    private function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    public static function encode(string $plainPassword): self
    {
        return new self(self::hash($plainPassword));
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function match(string $plainPassword): bool
    {
        return \password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function hash(string $plainPassword): string
    {
        // Max length.
        Assert::maxLength($plainPassword, PasswordHasherInterface::MAX_PASSWORD_LENGTH, 'Password too long');

        // Min length.
        Assert::minLength($plainPassword, self::MIN_PASSWORD_LENGTH, 'Min 6 characters password');

        // Hash.
        $hashedPassword = \password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => self::COST]);

        if (\is_bool($hashedPassword)) {
            throw new \RuntimeException('Server error hashing password');
        }

        return (string) $hashedPassword;
    }

    public function toString(): string
    {
        return $this->hashedPassword;
    }

    public function __toString(): string
    {
        return $this->hashedPassword;
    }
}
