<?php

namespace Ui\Http\Rest\ApiResource\Auth;

use App\User\Application\Query\Dto\AuthJwtTokenOutput;
use ApiPlatform\Core\Annotation\ApiResource;
use App\User\Application\Command\Dto\RefreshTokenInput;
use App\User\Application\Command\Dto\PasswordResetInput;
use App\User\Application\Command\Dto\LogoutInput;

/**
 * @ApiResource(
 *     routePrefix=AuthJwtTokenOutput::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_refresh_api"={
 *              "status"=200,
 *              "path"="/refresh",
 *              "input"=RefreshTokenInput::class,
 *              "output"=AuthJwtTokenOutput::class,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Refresh user access token",
 *                  "description"="Refresh user access token"
 *              }
 *         },
 *         "post_logout_api"={
 *              "status"=202,
 *              "path"="/logout",
 *              "input"=LogoutInput::class,
 *              "output"=false,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Logout user",
 *                  "description"="Logout user, remove session and device"
 *              },
 *         },
 *         "post_password_reset_api"={
 *              "status"=202,
 *              "path"="/password-reset",
 *              "input"=PasswordResetInput::class,
 *              "output"=false,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Reset user password",
 *                  "description"="Initiate a user reset password flow by sending a mail to the user"
 *              }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="Authentication",
 *     normalizationContext={
 *          "groups"={"auth:api-get", "api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"auth:api-post", "api-post"},
 *          "swagger_definition_name"="POST"
 *     }
 * )
 */
final class Authentication
{
    public $id;
}
