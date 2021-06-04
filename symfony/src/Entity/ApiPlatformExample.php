<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApiPlatformExampleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\LocationMinimalProperties;

/**
 * ApiPlatformExample example entity using API Platform, enable it using schema:update or remove if unnecessary.
 *
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post"
 *     },
 *     itemOperations={
 *         "get",
 *         "put",
 *         "patch"
 *     },
 *     shortName="Examples",
 *     normalizationContext={
 *          "groups"={"examples:api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"examples:api-write"},
 *          "swagger_definition_name"="POST"
 *     },
 * )
 * @ORM\Table(name="icapps_examples")
 * @ORM\Entity(repositoryClass=ApiPlatformExampleRepository::class)
 */
class ApiPlatformExample
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"examples:api-get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"examples:api-get", "examples:api-write"})
     *
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="array")
     *
     * @Groups({"examples:api-get", "examples:api-write"})
     *
     * @Assert\NotBlank
     * @LocationMinimalProperties()
     *
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="object",
     *             "required"={"lat", "lon"},
     *             "properties"={
     *                  "lat"={"type"="float", "example"="50.12345"},
     *                  "lon"={"type"="float", "example"="4.12345"}
     *             }
     *        }
     *    }
     * )
     */
    private $location = [];

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({"examples:api-get", "examples:api-write"})
     *
     * @Assert\NotBlank
     *
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "enum"={"api-platform", "php8"},
     *             "example"="api-platform"
     *         }
     *     }
     * )
     */
    public $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): ?array
    {
        return $this->location;
    }

    public function setLocation(array $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
