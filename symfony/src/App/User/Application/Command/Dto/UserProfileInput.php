<?php

declare(strict_types=1);

namespace App\User\Application\Command\Dto;

use App\User\Infrastructure\Doctrine\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

final class UserProfileInput
{
    /**
     * @var string
     *
     * @Groups({"profile:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.firstname.min_length",
     *     maxMessage="icapps.registration.firstname.max_length"
     * )
     */
    public string $firstName = '';

    /**
     * @var string
     *
     * @Groups({"profile:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.lastname.min_length",
     *     maxMessage="icapps.registration.lastname.max_length"
     * )
     */
    public string $lastName = '';

    /**
     * @var string
     *
     * @Groups({"profile:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Choice(
     *     message="icapps.registration.language.invalid",
     *     callback={User::class, "getAvailableLanguages"}
     * )
     */
    public string $language = '';
}
