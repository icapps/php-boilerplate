<?php

declare(strict_types=1);

namespace App\Entity;

use App\Component\Model\EntityIdInterface;
use App\Component\Model\Traits\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="icapps_devices")
 *
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device implements AuditableInterface, EntityIdInterface
{
    use AuditableTrait;
    use EntityIdTrait;

    public const RESOURCE_KEY = 'device';

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\App\Entity\User", inversedBy="device")
     */
    private User $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private string $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private string $deviceToken;

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
