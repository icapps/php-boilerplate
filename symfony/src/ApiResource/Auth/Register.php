<?php

namespace App\ApiResource\Auth;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Auth\AuthAccessDto;
use App\Dto\Auth\UserRegisterDto;
use App\Dto\User\UserProfileDto;

/**
 * @ApiResource(
 *     routePrefix=AuthAccessDto::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_register_api"={
 *              "path"= "/register",
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Register a new user",
 *                  "description"="Register a new user"
 *              },
 *              "input"=UserRegisterDto::class
 *         }
 *     },
 *     itemOperations={},
 *     shortName="Register",
 *     normalizationContext={
 *          "groups"={"api-get", "register:api-get"},
 *          "swagger_definition_name"="GET",
 *          "openapi_context"={
 *              "summary"="Register a new user",
 *              "description"="Register a new user"
 *          }
 *     },
 *     denormalizationContext={
 *          "groups"={"register:api-post"},
 *          "swagger_definition_name"="WRITE",
 *          "openapi_context"={
 *              "summary"="Register a new user",
 *              "description"="Register a new user"
 *          }
 *     },
 *     output=UserProfileDto::class
 * )
 */
class Register
{
    public $id;
}