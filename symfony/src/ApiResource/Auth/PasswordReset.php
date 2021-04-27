<?php

namespace App\ApiResource\Auth;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Auth\AuthAccessDto;
use App\Dto\General\StatusDto;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix=AuthAccessDto::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_password_reset_api"={
 *              "path"= "/password-reset",
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Reset user password",
 *                  "description"="Initiate a user reset password flow by sending a mail to the user"
 *              }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="Password reset",
 *     normalizationContext={
 *          "groups"={"password-reset:api-get", "api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"password-reset:api-write", "api-write"},
 *          "swagger_definition_name"="WRITE"
 *     },
 *     output=StatusDto::class
 * )
 */
class PasswordReset
{
    /**
     * @var string
     *
     * @Groups({"password-reset:api-write"})
     *
     * @Assert\NotBlank()
     */
    public string $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
