<?php

namespace App\ApiResource\User;

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
 *              "path"= "/profile/{id}",
 *              "openapi_context"={
 *                  "summary"="Get active user profile",
 *                  "description"="Get active user profile"
 *              }
 *         },
 *         "patch"={
 *              "path"= "/profile/{id}",
 *              "openapi_context"={
 *                  "summary"="Update user profile",
 *                  "description"="Update user profile"
 *              }
 *         },
 *         "password_update"={
 *              "status"=200,
 *              "path"="/profile/{id}/password",
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
 *          "groups"={"profile:api-post", "password:api-post"},
 *          "swagger_definition_name"="PATCH"
 *     },
 *     input=UserProfileDto::class,
 *     output=UserProfileDto::class
 * )
 */
final class User
{
    public $id;
}
