<?php

namespace App\User\Infrastructure\Model\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait with basic implementation of EnableInterface.
 */
trait EnableTrait
{
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private bool $enabled;

    /**
     * @return $this
     */
    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
