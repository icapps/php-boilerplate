<?php

namespace App\User\Infrastructure\Model\Traits;

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
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
