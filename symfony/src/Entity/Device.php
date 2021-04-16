<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="icapps_devices")
 *
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 *
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={
 *              "path"= "/user/devices/{deviceId}",
 *              "openapi_context"={
 *                  "summary"="Get user device",
 *                  "description"="Get user device"
 *              }
 *         },
 *         "patch"={
 *              "path"= "/user/devices/{deviceId}",
 *              "openapi_context"={
 *                  "summary"="Update user device token",
 *                  "description"="Update user device token"
 *              }
 *         }
 *     },
 *     shortName="UserDevice",
 *     normalizationContext={
 *          "groups"={"device:api-get", "api-get"},
 *          "swagger_definition_name"="GET"
 *     },
 *     denormalizationContext={
 *          "groups"={"device:api-write", "api-write"},
 *          "swagger_definition_name"="WRITE"
 *     }
 * )
 */
class Device
{
    const RESOURCE_KEY = 'device';

    /**
     * @var int|null
     *
     * @ApiProperty(identifier=false)
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\App\Entity\User", inversedBy="device")
     */
    private $user;

    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     * @Groups({"api-get"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private string $deviceId;

    /**
     * @var string
     *
     * @Groups({"api-write", "api-get", "orm-device"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private string $deviceToken;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     */
    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return string
     */
    public function getDeviceToken(): string
    {
        return $this->deviceToken;
    }

    /**
     * @param string $deviceToken
     */
    public function setDeviceToken(string $deviceToken): void
    {
        $this->deviceToken = $deviceToken;
    }
}
