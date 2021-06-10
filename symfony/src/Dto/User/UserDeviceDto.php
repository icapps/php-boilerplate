<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserDeviceDto
 *
 * All custom ApiResource responses should be included using a proper DTO.
 *
 * @package App\Dto
 */
final class UserDeviceDto
{
    public const USER_DEVICE_ROUTE_PREFIX = '/users/devices';
    public const USER_DEVICE_BUNDLE_TAG = 'User';

    /**
     * @var string
     *
     * @Groups({"api-get"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceId = '';

    /**
     * @var string
     *
     * @Groups({"api-get", "api-post"})
     *
     * @Assert\NotBlank()
     */
    public string $deviceToken = '';

    public function __construct(string $deviceId, string $deviceToken)
    {
        $this->deviceId = $deviceId;
        $this->deviceToken = $deviceToken;
    }
}
