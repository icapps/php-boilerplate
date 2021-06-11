<?php

namespace App\ApiResource\Auth;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Auth\AuthAccessDto;
use App\Dto\General\StatusDto;
use App\Dto\Auth\UserLogoutDto;
use App\Dto\Auth\UserPasswordResetDto;
use App\Dto\Auth\UserRefreshDto;
use App\Dto\Auth\UserEmailAvailableDto;
use App\Dto\Auth\UserResendRegistrationEmailDto;

/**
 * @ApiResource(
 *     routePrefix=AuthAccessDto::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_refresh_api"={
 *              "status"=200,
 *              "path"="/refresh",
 *              "input"=UserRefreshDto::class,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Refresh user access token",
 *                  "description"="Refresh user access token"
 *              }
 *         },
 *         "post_email_available_api"={
 *              "status"=200,
 *              "path"="/email-available",
 *              "input"=UserEmailAvailableDto::class,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Check if email is available",
 *                  "description"="Check if email is available"
 *              }
 *         },
 *         "post_resend_registration_email"={
 *              "status"=200,
 *              "path"="/resend-registration-email",
 *              "input"=UserResendRegistrationEmailDto::class,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Resend registration email",
 *                  "description"="Resend registration email"
 *              }
 *         },
 *         "post_logout_api"={
 *              "status"=200,
 *              "path"="/logout",
 *              "input"=UserLogoutDto::class,
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Logout user",
 *                  "description"="Logout user, remove session and device"
 *              },
 *         },
 *         "post_password_reset_api"={
 *              "status"=200,
 *              "path"="/password-reset",
 *              "input"=UserPasswordResetDto::class,
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
 *     },
 *     output=StatusDto::class
 * )
 */
final class Authentication
{
    public $id;
}
