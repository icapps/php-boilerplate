<?php

namespace App\Component\Model;

/**
 * User Profiles implementing this interface must have these methods for automation boilerplate
 */
interface ProfileInterface extends EnableInterface
{
    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName);

    /**
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName);

    /**
     * @return string|null
     */
    public function getLastName(): ?string;
}
