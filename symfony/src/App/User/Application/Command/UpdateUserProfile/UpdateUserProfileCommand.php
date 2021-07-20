<?php

declare(strict_types=1);

namespace App\User\Application\Command\UpdateUserProfile;

use App\Shared\Infrastructure\Bus\Command\CommandInterface;
use Webmozart\Assert\InvalidArgumentException;

final class UpdateUserProfileCommand implements CommandInterface
{
    public int $id;

    public string $language;

    public string $firstName;

    public string $lastName;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(int $id, string $language, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->language = $language;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
