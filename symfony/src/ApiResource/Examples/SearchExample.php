<?php

namespace App\ApiResource\Examples;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Dto\Examples\SearchExampleDto;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This is a custom API Platform ApiResource, with DataPersister and DataProvider.
 *
 * @ApiResource(
 *     collectionOperations={
 *         "post_search_api"={
 *              "method"="POST",
 *              "openapi_context"={
 *                  "summary"="POST Search Examples",
 *                  "description"="API Platform custom Search example"
 *              }
 *         },
 *         "get_search_api"={
 *              "method"="GET",
 *              "openapi_context"={
 *                  "summary"="GET Search Examples",
 *                  "description"="API Platform custom Search example"
 *              }
 *         }
 *     },
 *     itemOperations={
 *         "get_search_detail_api"={
 *              "method"="GET",
 *              "openapi_context"={
 *                  "summary"="GET Search detail Example",
 *                  "description"="API Platform custom Search example"
 *              }
 *         }
 *     },
 *     shortName="SearchExample",
 *     normalizationContext={
 *          "groups"={"search-examples:api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"search-examples:api-post"},
 *          "swagger_definition_name"="POST"
 *     },
 *     output=SearchExampleDto::class
 * )
 */
class SearchExample
{
    /**
     * @var int
     *
     * @ApiProperty(identifier=true)
     */
    public int $id;

    /**
     * @var string
     *
     * @Groups({"search-examples:api-post"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 3,
     *     max = 15,
     *     minMessage="Minimum length 3 characters",
     *     maxMessage="Maximum length 15 characters"
     * )
     */
    public string $keyword;

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }
}
