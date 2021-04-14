<?php

namespace App\ApiResource\Authentication;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\AuthAccessOutput;
use App\Dto\UserProfileOutput;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix=AuthAccessOutput::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_register_api"={
 *              "path"= "/register",
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Register a new user",
 *                  "description"="Register a new user"
 *              }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="Register",
 *     normalizationContext={
 *          "groups"={"register:api-get", "api-get"},
 *          "swagger_definition_name"="GET",
 *          "openapi_context"={
 *              "summary"="Register a new user",
 *              "description"="Register a new user"
 *          }
 *     },
 *     denormalizationContext={
 *          "groups"={"register:api-write", "api-write"},
 *          "swagger_definition_name"="WRITE",
 *          "openapi_context"={
 *              "summary"="Register a new user",
 *              "description"="Register a new user"
 *          }
 *     },
 *     output=UserProfileOutput::class
 * )
 */
class Register
{
    /**
     * @var string
     *
     * @Groups({"register:api-write"})
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
     * @Groups({"register:api-write"})
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
     * @Groups({"register:api-write"})
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
     * @Groups({"register:api-write"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 8,
     *     max = 255,
     *     minMessage="Minimim length 8 characters",
     *     maxMessage="Maximum length 50 characters"
     * )
     */
    public string $password;

    /**
     * @var string
     *
     * @Groups({"register:api-write"})
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

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
