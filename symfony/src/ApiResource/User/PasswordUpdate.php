<?php

namespace App\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\User\UserProfileDto;
use App\Dto\User\UserPasswordDto;
use App\Dto\General\StatusDto;

/**
 * @ApiResource(
 *     routePrefix=UserProfileDto::USER_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post"={
 *              "path"= "/password-update",
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Update user password",
 *                  "description"="Update user password"
 *              }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="UserPassword",
 *     normalizationContext={},
 *     denormalizationContext={
 *          "groups"={"password:api-post"},
 *          "swagger_definition_name"="POST"
 *     },
 *     input=UserPasswordDto::class,
 *     output=StatusDto::class
 * )
 */
class PasswordUpdate
{
    public $id;
}
