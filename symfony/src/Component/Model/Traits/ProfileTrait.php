<?php

namespace App\Component\Model\Traits;

use App\Component\Model\ProfileInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait with basic implementation of ProfileInterface.
 */
trait ProfileTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="icapps.registration.firstname.required",
     *     groups={"orm-registration", "orm-profile-update"}
     * )
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="icapps.registration.lastname.required",
     *     groups={"orm-registration", "orm-profile-update"}
     * )
     */
    protected string $lastName;

    /**
     * {@inheritDoc}
     */
    public function setFirstName(string $firstName): ProfileInterface
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * {@inheritDoc}
     */
    public function setLastName(string $lastName): ProfileInterface
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
