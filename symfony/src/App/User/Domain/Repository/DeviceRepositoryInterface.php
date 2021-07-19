<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Infrastructure\Doctrine\Device;

interface DeviceRepositoryInterface
{
    /**
     * @return Device
     */
    public function create(): Device;

    /**
     * @param Device $device
     */
    public function store(Device $device): void;

    /**
     * @param Device $device
     */
    public function remove(Device $device): void;
}
