<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Component\Model\ProfileInterface;
use App\Component\Model\Traits\ProfileTrait;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={}
 * )
 * @ORM\Table(name="icapps_profiles")
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile implements ProfileInterface, AuditableInterface
{
    use AuditableTrait;
    use ProfileTrait;

    const RESOURCE_KEY = 'profiles';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
