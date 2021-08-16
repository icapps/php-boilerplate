<?php

namespace App\Component\Model;

/**
 * User Profiles implementing this interface must have these methods for automation boilerplate
 */
interface ProfileInterface
{
    /**
     * Set profile firstname.
     *
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName): ProfileInterface;

    /**
     * Get profile firstname.
     *
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Set profile lastname.
     *
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): ProfileInterface;

    /**
     * Get profile last name.
     *
     * @return string
     */
    public function getLastName(): string;
}
