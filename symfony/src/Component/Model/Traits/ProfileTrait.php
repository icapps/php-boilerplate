<?php

namespace App\Component\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait with basic implementation of ProfileInterface.
 */
trait ProfileTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="icapps.registration.firstname.required", groups={"registration", "profile-update"})
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.firstname.min_length",
     *     maxMessage="icapps.registration.firstname.max_length",
     *     allowEmptyString = false,
     *     groups={"registration", "profile-update", "admin", "api-write"}
     * )
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="icapps.registration.lastname.required", groups={"registration", "profile-update"})
     * @Assert\Length(
     *     min = 1,
     *     max = 50,
     *     minMessage="icapps.registration.lastname.min_length",
     *     maxMessage="icapps.registration.lastname.max_length",
     *     allowEmptyString = false,
     *     groups={"registration", "profile-update", "admin"}
     * )
     */
    protected string $lastName;

    /**
     * @param $firstName
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
     * @param $lastName
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
