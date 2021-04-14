<?php

namespace App\ApiResource\Authentication;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\AuthAccessOutput;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix=AuthAccessOutput::AUTH_ROUTE_PREFIX,
 *     collectionOperations={
 *         "post_refresh_api"={
 *              "path"= "/refresh",
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="Refresh user access token",
 *                  "description"="Refresh user access token"
 *              }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="Refresh",
 *     normalizationContext={
 *          "groups"={"refresh:api-get", "api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"refresh:api-write", "api-write"},
 *          "swagger_definition_name"="WRITE"
 *     },
 *     output=AuthAccessOutput::class
 * )
 */
class Refresh
{
    /**
     * @var string
     *
     * @Groups({"refresh:api-write"})
     *
     * @Assert\NotBlank()
     */
    public string $refreshToken;

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
