<?php

namespace App\ApiResource\Authentication;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\AuthAccessDto;
use App\Dto\StatusDto;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix=AuthAccessDto::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_logout_api"={
 *              "path"= "/logout",
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Logout user",
 *                  "description"="Logout user, remove session and device"
 *              }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="Logout",
 *     normalizationContext={
 *          "groups"={"logout:api-get", "api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"logout:api-write", "api-write"},
 *          "swagger_definition_name"="WRITE"
 *     },
 *     output=StatusDto::class
 * )
 */
class Logout
{
    /**
     * @var string
     *
     * @Groups({"logout:api-write"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceId;

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }
}
