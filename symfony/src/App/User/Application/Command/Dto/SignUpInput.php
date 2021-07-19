<?php

declare(strict_types=1);

namespace App\User\Application\Command\Dto;

use App\User\Infrastructure\Doctrine\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

final class SignUpInput
{
    public const AUTH_ROUTE_PREFIX = '/auth';
    public const AUTH_BUNDLE_TAG = 'Authentication';

    /**
     * @var string
     *
     * @Groups({"register:api-post"})
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
    public string $firstName;

    /**
     * @var string
     *
     * @Groups({"register:api-post"})
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
    public string $lastName;

    /**
     * @var string
     *
     * @Groups({"register:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     message="icapps.registration.email.invalid",
     * )
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.email.min_length",
     *     maxMessage="icapps.registration.email.max_length"
     * )
     */
    public string $email;

    /**
     * @var string
     *
     * @Groups({"register:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Choice(
     *     message="icapps.registration.language.invalid",
     *     callback={User::class, "getAvailableLanguages"}
     * )
     */
    public string $language;

    /**
     * @var string
     *
     * @Groups({"register:api-post"})
     *
     * @Assert\NotBlank()
     * @Assert\NotCompromisedPassword()
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.password.min_length",
     *     maxMessage="icapps.registration.password.max_length"
     * )
     */
    public string $password;
}
