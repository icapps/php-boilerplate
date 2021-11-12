<?php

namespace App\ApiResource\User;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\User\UserDeviceDto;

/**
 * @ApiResource(
 *     routePrefix=UserDeviceDto::USER_DEVICE_ROUTE_PREFIX,
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={
 *              "path"= "/{deviceSid}",
 *              "method"="GET",
 *              "openapi_context"={
 *                  "summary"="Get user device",
 *                  "description"="Get user device"
 *              }
 *         },
 *         "update"={
 *              "path"= "/{deviceSid}",
 *              "method"="PATCH",
 *              "openapi_context"={
 *                  "summary"="Update user device token",
 *                  "description"="Update user device token"
 *              }
 *         }
 *     },
 *     normalizationContext={
 *          "groups"={"api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"api-post"},
 *          "swagger_definition_name"="PATCH"
 *     },
 *     shortName="Devices",
 *     input=UserDeviceDto::class,
 *     output=UserDeviceDto::class
 * )
 */
final class UserDevice
{
    /**
     * @ApiProperty(identifier=true)
     */
    public string $deviceSid;
}
