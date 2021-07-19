<?php

namespace App\User\Application\Query\Dto;

use App\User\Infrastructure\Doctrine\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UserProfileOutput
{
    public const USER_ROUTE_PREFIX = '/users';
    public const USER_BUNDLE_TAG = 'User';

    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     *
     * @Groups({"api-get", "profile:api-get"})
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
     * @Groups({"api-get", "profile:api-get"})
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
     * @Groups({"api-get", "profile:api-get"})
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
     * @Groups({"api-get", "profile:api-get"})
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
     * Create DTO from given entity.
     *
     * @param User $user
     *
     * @return static
     */
    public static function fromEntity(User $user): self
    {
        $profile = $user->getProfile();
        $userProfileDto = new self();
        $userProfileDto->id = $user->getId();
        $userProfileDto->firstName = $profile->getFirstName();
        $userProfileDto->lastName = $profile->getLastName();
        $userProfileDto->email = $user->getEmail();
        $userProfileDto->language = $user->getLanguage();

        return $userProfileDto;
    }
}
