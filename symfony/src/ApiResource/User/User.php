<?php

namespace App\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\General\StatusDto;
use App\Dto\User\UserProfileDto;
use App\Dto\User\UserPasswordDto;

/**
 * @ApiResource(
 *     routePrefix=UserProfileDto::USER_ROUTE_PREFIX,
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={
 *              "path"= "/{userSid}/profile",
 *              "openapi_context"={
 *                  "summary"="Get active user profile",
 *                  "description"="Get active user profile"
 *              }
 *         },
 *         "patch"={
 *              "path"= "/{userSid}/profile",
 *              "openapi_context"={
 *                  "summary"="Update user profile",
 *                  "description"="Update user profile"
 *              }
 *         },
 *         "password_update"={
 *              "status"=200,
 *              "path"="/{userSid}/password",
 *              "input"=UserPasswordDto::class,
 *              "output"=StatusDto::class,
 *              "method"="PATCH",
 *              "openapi_context"={
 *                  "summary"="Update user password",
 *                  "description"="Update user password"
 *              },
 *         },
 *     },
 *     normalizationContext={
 *          "groups"={"api-get", "profile:api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"api-post", "profile:api-post", "password:api-post"},
 *          "swagger_definition_name"="PATCH"
 *     },
 *     input=UserProfileDto::class,
 *     output=UserProfileDto::class
 * )
 */
final class User
{
    /**
     * @ApiProperty(identifier=true)
     *
     * The user string identifier.
     */
    public string $userSid;
}
