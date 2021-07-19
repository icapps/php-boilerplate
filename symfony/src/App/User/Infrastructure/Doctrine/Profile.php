<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine;

use App\User\Infrastructure\Model\ProfileInterface;
use App\User\Infrastructure\Model\Traits\ProfileTrait;
use App\User\Infrastructure\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @ORM\Table(name="icapps_profiles")
 *
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile implements ProfileInterface, AuditableInterface
{
    use AuditableTrait;
    use ProfileTrait;

    public const RESOURCE_KEY = 'profiles';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Create profile object.
     *
     * @param string $firstName
     * @param string $lastName
     *
     * @return static
     */
    public static function create(string $firstName, string $lastName): self
    {
        $profile = new self();
        $profile->setFirstName($firstName);
        $profile->setLastName($lastName);

        return $profile;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
