<?php

declare(strict_types=1);

namespace App\User\Application\Command\SignUp;

use App\Shared\Infrastructure\Bus\Command\CommandInterface;
use App\User\Domain\ValueObject\Auth\HashedPassword;
use App\User\Domain\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\InvalidArgumentException;

final class SignUpCommand implements CommandInterface
{
    public UuidInterface $uuid;

    public string $firstName;

    public string $lastName;

    public string $language;

    public Email $email;

    public HashedPassword $password;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $language,
        string $plainPassword
    ) {
        $this->uuid = Uuid::fromDateTime(new \DateTime());
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = Email::fromString($email);
        $this->language = $language;
        $this->password = HashedPassword::encode($plainPassword);
    }
}
