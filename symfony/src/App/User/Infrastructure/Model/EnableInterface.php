<?php

namespace App\User\Infrastructure\Model;

/**
 * Entities implementing this interface keep a state of enabled. Must use with User Profiles!
 */
interface EnableInterface
{
    /**
     * Return if the profile is enabled
     *
     * @return boolean
     */
    public function isEnabled(): bool;

    /**
     * Set enable to true
     */
    public function enable();

    /**
     * Set enable to false
     */
    public function disable();
}
