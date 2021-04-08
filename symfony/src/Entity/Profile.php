<?php

declare(strict_types=1);

namespace App\Entity;

use App\Component\Model\ProfileInterface;
use App\Component\Model\Traits\ProfileTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="icapps_profiles")
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile implements ProfileInterface// AuditableInterface // TODO: implement SULU interfaces
{

    //use AuditableTrait; // TODO: implement SULU trait
    use ProfileTrait;
    const RESOURCE_KEY = 'profiles';
    const PROFILE_TYPE = 'profile';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
