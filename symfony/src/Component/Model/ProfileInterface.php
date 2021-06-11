<?php

namespace App\Component\Model;

/**
 * User Profiles implementing this interface must have these methods for automation boilerplate
 */
interface ProfileInterface
{
    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName);

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName);

    /**
     * @return string
     */
    public function getLastName(): string;
}
