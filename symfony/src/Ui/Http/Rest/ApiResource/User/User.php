<?php

namespace Ui\Http\Rest\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\User\Application\Command\Dto\UserProfileInput;
use App\User\Application\Query\Dto\UserProfileOutput;

/**
 * @ApiResource(
 *     routePrefix=UserProfileOutput::USER_ROUTE_PREFIX,
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={
 *              "path"= "/{id}/profile",
 *              "openapi_context"={
 *                  "summary"="Get active user profile",
 *                  "description"="Get active user profile"
 *              }
 *         },
 *         "patch"={
 *              "path"= "/{id}/profile",
 *              "output"=false,
 *              "status"=202,
 *              "openapi_context"={
 *                  "summary"="Update user profile",
 *                  "description"="Update user profile"
 *              }
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
 *     input=UserProfileInput::class,
 *     output=UserProfileOutput::class
 * )
 */
final class User
{
    public $id;
}
