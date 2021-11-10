<?php

namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserRegisterDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 */
final class UserRegisterDto
{
    /**
     * @var string
     *
     * @Groups({"register:api-get", "register:api-post"})
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
     * @Groups({"register:api-get", "register:api-post"})
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
     * @Groups({"register:api-get", "register:api-post"})
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
     * @Groups({"register:api-get", "register:api-post"})
     *
     * @Assert\NotBlank()
     *
     * @Assert\Choice(
     *     message="icapps.registration.language.invalid",
     *     callback={"App\Entity\User", "getAvailableLanguages"}
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
