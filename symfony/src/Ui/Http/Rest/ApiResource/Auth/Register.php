<?php

namespace Ui\Http\Rest\ApiResource\Auth;

use ApiPlatform\Core\Annotation\ApiResource;
use App\User\Application\Command\Dto\SignUpInput;

/**
 * @ApiResource(
 *     routePrefix=SignUpInput::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_register_api"={
 *              "path"= "/register",
 *              "method"="POST",
 *              "status"=202,
 *              "openapi_context"={
 *                  "summary"="Register a new user",
 *                  "description"="Register a new user"
 *              }
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
 *          "swagger_definition_name"="POST",
 *          "openapi_context"={
 *              "summary"="Register a new user",
 *              "description"="Register a new user"
 *          }
 *     },
 *     input=SignUpInput::class,
 *     output=false
 * )
 */
final class Register
{
    public $id;
}
