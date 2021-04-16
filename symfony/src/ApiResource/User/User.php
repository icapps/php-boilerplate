<?php

namespace App\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\User\UserProfileDto;

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
 *              },
 *              "input"=UserProfileDto::class
 *         },
 *     },
 *     normalizationContext={
 *          "groups"={"profile:api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"profile:api-post"},
 *          "swagger_definition_name"="WRITE"
 *     },
 *     output=UserProfileDto::class
 * )
 */
class User
{
    public $id;
}
