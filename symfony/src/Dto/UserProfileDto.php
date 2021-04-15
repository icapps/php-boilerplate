<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserProfileDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
class UserProfileDto
{
    const USER_ROUTE_PREFIX = '/user';

    /**
     * @var string
     *
     * @Groups({"api-get", "api-write"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="Minimim length 1 characters",
     *     maxMessage="Maximum length 50 characters"
     * )
     */
    public string $firstName;

    /**
     * @var string
     *
     * @Groups({"api-get", "api-write"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="Minimim length 1 characters",
     *     maxMessage="Maximum length 50 characters"
     * )
     */
    public string $lastName;

    /**
     * @var string
     *
     * @Groups({"api-get", "api-write"})
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="Minimim length 1 characters",
     *     maxMessage="Maximum length 50 characters"
     * )
     */
    public string $email;
    
    /**
     * @var string
     *
     * @Groups({"api-get", "api-write"})
     *
     * @Assert\NotBlank()
     * @Assert\Locale(
     *     canonicalize = true
     * )
     * @Assert\Choice(
     *     message="This value is not a valid language.",
     *     callback={"App\Entity\User", "getAvailableLanguages"}
     * )
     */
    public string $language;
}
